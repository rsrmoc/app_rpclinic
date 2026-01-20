require('./bootstrap');
import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';
Alpine.plugin(mask);
window.Alpine = Alpine;

document.addEventListener("DOMContentLoaded", (e) => {
    Alpine.start();
    document.querySelector('#rpclinica-loader')?.classList?.add('rpclinica-loader-hide');
});

// Configuração global do Toastr para aparecer embaixo
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-bottom-full-width", // Força aparecer embaixo
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

window.parseErrorsAPI = (error) => {
    // Caso 1: Erro de Validação do Laravel (422) com lista de erros
    if (error.response && error.response.data && error.response.data.errors) {
        Object.keys(error.response.data.errors).forEach((keyError) => {
            error.response.data.errors[keyError].forEach((msg) => toastr['error'](msg));
        });
        return;
    }

    // Caso 2: Erro com mensagem definida pelo backend (Ex: Exception manual)
    if (error.response && error.response.data && error.response.data.message) {
        toastr['error'](error.response.data.message);
        return;
    }

    // Caso 3: Erro genérico do Axios ou JavaScript (sem response do backend)
    if (error.message) {
        toastr['error'](error.message);
        return;
    }

    // Caso 4: Fallback final
    toastr['error']('Ocorreu um erro desconhecido na requisição.');
};


window.delete_cadastro = function (url, el) {
    Swal.fire({
        title: 'Confirmação',
        text: "Tem certeza que deseja excluir esse cadastro?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22BAA0',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post(url)
                .then((res) => {
                    document.querySelector(el).remove();
                    toastr["success"]('Cadastro excluido com sucesso!');
                })
                .catch((err) => toastr["error"]('Não foi possivel excluir o cadastro!'));
        }
    });
}
