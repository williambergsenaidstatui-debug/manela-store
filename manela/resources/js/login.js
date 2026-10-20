$(document).ready(function () {
    $("#toggle-password").click(function () {
        const campoSenha = $("#senha");
        const mostrarSenha = campoSenha.attr("type") === "password";
        campoSenha.attr("type", mostrarSenha ? "text" : "password");
        $(this).toggleClass("is-visible", mostrarSenha);
    });

    $("#login-form").submit(function (event) {
        event.preventDefault();

        $.ajax({
            url: "/api/login",
            method: "POST",
            data: { email: $("#email").val(), senha: $("#senha").val() },
            success: function (response) {
                sessionStorage.setItem("tif_token", response.token);
                sessionStorage.setItem("san_doces_usuario", JSON.stringify(response.usuario));
                Swal.fire({
                    icon: "success",
                    title: "Sucesso!",
                    text: response.mensagem
                }).then(function () {
                    if (response.usuario.is_admin) {
                        window.location.href = "/dashboard";
                    } else {
                        window.location.href = "/";
                    }
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Erro!",
                    text: xhr.responseJSON?.mensagem || xhr.responseJSON?.message || "Não foi possível entrar."
                });
            }
        });
    });
});
