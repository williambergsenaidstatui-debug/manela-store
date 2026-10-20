import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
import { DecalGeometry } from 'three/addons/geometries/DecalGeometry.js';
import { mergeGeometries } from 'three/addons/utils/BufferGeometryUtils.js';

const viewer = document.querySelector('[data-shirt-viewer]');
const stage = viewer.querySelector('[data-shirt-stage]');
const status = viewer.querySelector('[data-shirt-status]');
const toolbar = viewer.querySelector('[data-shirt-toolbar]');
const pauseButton = viewer.querySelector('[data-shirt-pause]');
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
const defaultShirtColor = '#000000';
const whiteShirtColor = '#eae8e2';

function createCottonTexture() {
    const canvas = document.createElement('canvas');
    canvas.width = 128;
    canvas.height = 128;
    const context = canvas.getContext('2d');
    const pixels = context.createImageData(128, 128);
    for (let y = 0; y < 128; y++) {
        for (let x = 0; x < 128; x++) {
            const index = (y * 128 + x) * 4;
            const weave = ((x + y) % 4 === 0 ? -7 : 0) + ((x * 73 + y * 37) % 9);
            const shade = 238 + weave;
            pixels.data[index] = shade;
            pixels.data[index + 1] = shade;
            pixels.data[index + 2] = shade;
            pixels.data[index + 3] = 255;
        }
    }
    context.putImageData(pixels, 0, 0);
    const texture = new THREE.CanvasTexture(canvas);
    texture.colorSpace = THREE.SRGBColorSpace;
    texture.wrapS = THREE.RepeatWrapping;
    texture.wrapT = THREE.RepeatWrapping;
    texture.repeat.set(3, 3);
    texture.anisotropy = 4;
    return texture;
}

function prepareShirt(model) {
    const cottonTexture = createCottonTexture();
    const fabricMaterial = new THREE.MeshStandardMaterial({ color: defaultShirtColor, map: cottonTexture, roughness: .96, metalness: 0, side: THREE.DoubleSide });
    const oldMaterials = new Set();
    model.traverse((object) => {
        if (!object.isMesh) return;
        const materials = Array.isArray(object.material) ? object.material : [object.material];
        materials.forEach((material) => oldMaterials.add(material));
        object.material = fabricMaterial;
    });
    oldMaterials.forEach((material) => material.dispose());
    return { cottonTexture, fabricMaterial };
}

async function addChestLogo(shirt, model) {
    shirt.updateMatrixWorld(true);
    const bounds = new THREE.Box3().setFromObject(shirt);
    const chestRay = new THREE.Raycaster(
        new THREE.Vector3(bounds.max.x * .34, bounds.max.y * .41, bounds.max.z + 2),
        new THREE.Vector3(0, 0, -1),
    );
    const meshes = [];
    model.traverse((object) => { if (object.isMesh) meshes.push(object); });
    const chest = chestRay.intersectObjects(meshes, false)[0];
    if (!chest) throw new Error('Peito da camiseta não encontrado.');

    const decalSize = new THREE.Vector3(.30, .38, .12);
    const decals = meshes.map((mesh) => new DecalGeometry(mesh, chest.point, new THREE.Euler(), decalSize));
    const visibleDecals = decals.filter((geometry) => geometry.getAttribute('position').count > 0);
    const geometry = mergeGeometries(visibleDecals);
    decals.forEach((decal) => decal.dispose());
    if (!geometry) throw new Error('Não foi possível posicionar a logo.');
    geometry.applyMatrix4(shirt.matrixWorld.clone().invert());

    const logoTexture = await new THREE.TextureLoader().loadAsync('/assets/lm-logo.svg');
    logoTexture.colorSpace = THREE.SRGBColorSpace;
    const logoMaterial = new THREE.MeshBasicMaterial({
        map: logoTexture,
        transparent: true,
        alphaTest: .05,
        depthWrite: false,
        polygonOffset: true,
        polygonOffsetFactor: -3,
        side: THREE.DoubleSide,
    });
    const logo = new THREE.Mesh(geometry, logoMaterial);
    logo.renderOrder = 2;
    shirt.add(logo);
    return logoMaterial;
}

async function initializeShirt() {
    let renderer;
    try {
        renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    } catch {
        status.textContent = 'Camiseta LM · Estilo em cada detalhe';
        return;
    }

    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.5));
    renderer.setClearColor(0x000000, 0);
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.4;
    renderer.domElement.setAttribute('aria-label', 'Modelo 3D interativo da camiseta LM');
    renderer.domElement.setAttribute('role', 'img');
    stage.appendChild(renderer.domElement);

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(35, 1, 0.01, 100);
    camera.position.set(0, .08, 1);
    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.enablePan = false;
    controls.enableZoom = false;
    controls.autoRotate = !reducedMotion.matches;
    controls.autoRotateSpeed = .8;
    controls.minPolarAngle = Math.PI * .28;
    controls.maxPolarAngle = Math.PI * .68;
    controls.target.set(0, 0, 0);

    scene.add(new THREE.HemisphereLight(0xffe5c4, 0x22201e, 1.1));
    const keyLight = new THREE.SpotLight(0xffcd94, 50, 8, Math.PI / 4, .8);
    keyLight.position.set(0, 3.8, 2.8);
    keyLight.target.position.set(0, 0, 0);
    scene.add(keyLight);
    scene.add(keyLight.target);
    const fillLight = new THREE.DirectionalLight(0xd7e1ef, 2.1);
    fillLight.position.set(-3, 1, 4);
    scene.add(fillLight);
    const rimLight = new THREE.DirectionalLight(0xffc890, 2.6);
    rimLight.position.set(2, 2.5, -3);
    scene.add(rimLight);

    let model;
    try {
        model = (await new GLTFLoader().loadAsync(viewer.dataset.model)).scene;
    } catch {
        status.textContent = 'Camiseta LM · Estilo em cada detalhe';
        controls.dispose();
        renderer.dispose();
        renderer.domElement.remove();
        return;
    }

    const { fabricMaterial } = prepareShirt(model);
    const bounds = new THREE.Box3().setFromObject(model);
    const center = bounds.getCenter(new THREE.Vector3());
    const size = bounds.getSize(new THREE.Vector3());
    model.position.sub(center);
    const shirt = new THREE.Group();
    shirt.add(model);
    shirt.scale.setScalar(2.6 / Math.max(size.x, size.y, size.z));
    shirt.rotation.y = -.2;
    scene.add(shirt);
    let logoMaterial;
    try {
        logoMaterial = await addChestLogo(shirt, model);
    } catch (error) {
        console.error('Não foi possível adicionar o brasão à camiseta 3D:', error);
    }
    const radius = new THREE.Box3().setFromObject(shirt).getBoundingSphere(new THREE.Sphere()).radius;

    viewer.querySelectorAll('[data-shirt-color]').forEach((button) => {
        button.addEventListener('click', () => {
            fabricMaterial.color.set(button.dataset.shirtColor);
            logoMaterial?.color.set(button.dataset.shirtColor === whiteShirtColor ? '#000000' : '#ffffff');
            viewer.querySelectorAll('[data-shirt-color]').forEach((swatch) => {
                swatch.setAttribute('aria-pressed', String(swatch === button));
            });
        });
    });

    const resize = () => {
        const width = stage.clientWidth;
        const height = stage.clientHeight;
        if (!width || !height) return;
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
        const verticalAngle = THREE.MathUtils.degToRad(camera.fov / 2);
        const horizontalAngle = Math.atan(Math.tan(verticalAngle) * camera.aspect);
        const distance = radius / Math.sin(Math.min(verticalAngle, horizontalAngle)) * .82;
        camera.position.copy(camera.position.lengthSq() ? camera.position.normalize() : new THREE.Vector3(0, .08, 1).normalize()).multiplyScalar(distance);
        controls.update();
        renderer.setSize(width, height, false);
        renderer.render(scene, camera);
    };
    const resizeObserver = new ResizeObserver(resize);
    resizeObserver.observe(stage);
    resize();

    let paused = reducedMotion.matches;
    let onScreen = true;
    let lastTime;
    const updatePauseButton = () => {
        pauseButton.setAttribute('aria-pressed', String(paused));
        pauseButton.textContent = paused ? 'Retomar rotação' : 'Pausar rotação';
        controls.autoRotate = !paused;
    };
    updatePauseButton();
    pauseButton.addEventListener('click', () => {
        paused = !paused;
        updatePauseButton();
    });
    reducedMotion.addEventListener('change', () => {
        paused = reducedMotion.matches;
        updatePauseButton();
    });
    controls.addEventListener('start', () => {
        paused = true;
        updatePauseButton();
    });
    viewer.querySelector('[data-shirt-reset]').addEventListener('click', () => {
        camera.position.set(0, .08, 1);
        controls.target.set(0, 0, 0);
        shirt.rotation.y = -.2;
        resize();
    });

    const animate = (time) => {
        const delta = lastTime === undefined ? 0 : Math.min((time - lastTime) / 1000, .05);
        lastTime = time;
        controls.update(delta);
        renderer.render(scene, camera);
    };
    const updateVisibility = () => {
        lastTime = undefined;
        renderer.setAnimationLoop(onScreen && !document.hidden ? animate : null);
    };
    const intersectionObserver = new IntersectionObserver(([entry]) => {
        onScreen = entry.isIntersecting;
        updateVisibility();
    });
    intersectionObserver.observe(viewer);
    document.addEventListener('visibilitychange', updateVisibility);
    renderer.domElement.addEventListener('webglcontextlost', (event) => {
        event.preventDefault();
        renderer.setAnimationLoop(null);
        viewer.classList.remove('shirt-ready');
        toolbar.hidden = true;
        status.hidden = false;
        status.textContent = 'Camiseta LM · Estilo em cada detalhe';
    });
    viewer.classList.add('shirt-ready');
    status.hidden = true;
    toolbar.hidden = false;
    updateVisibility();
    window.addEventListener('pagehide', (event) => {
        if (event.persisted) return;
        resizeObserver.disconnect();
        intersectionObserver.disconnect();
        document.removeEventListener('visibilitychange', updateVisibility);
        renderer.setAnimationLoop(null);
        controls.dispose();
        shirt.traverse((object) => {
            if (!object.isMesh) return;
            object.geometry.dispose();
            const materials = Array.isArray(object.material) ? object.material : [object.material];
            materials.forEach((material) => { material.map?.dispose(); material.dispose(); });
        });
        renderer.dispose();
    });
}

initializeShirt().catch(() => {
    status.hidden = false;
    status.textContent = 'Camiseta LM · Estilo em cada detalhe';
    viewer.classList.remove('shirt-ready');
    toolbar.hidden = true;
});
