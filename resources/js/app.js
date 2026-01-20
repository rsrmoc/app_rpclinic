require('./bootstrap');
import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';
Alpine.plugin(mask);
window.Alpine = Alpine;

document.addEventListener("DOMContentLoaded", (e) => {
    Alpine.start();
    document.querySelector('#rpclinica-loader')?.classList?.add('rpclinica-loader-hide');
});

window.parseErrorsAPI = (error) => {
    if (error.response.data.errors) {
        Object.keys(error.response.data.errors).forEach((keyError) => {
            error.response.data.errors[keyError].forEach((error) => toastr['error'](error));
        });

        return;
    }

    toastr['error'](error.response.data.message);
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
