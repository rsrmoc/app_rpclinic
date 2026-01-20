import axios from 'axios';
import moment from 'moment';

Alpine.data('app', () => ({
    cdProfissional: cdProfissional,
    init() {

    },
    
    deleteAssinatura() {

        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir essa assinatura?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22baa0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) { 
                
                axios.delete(`/rpclinica/profissional-assinatura/${this.cdProfissional}`)
                    .then((res) => {
                        $("#img_assinatura").remove();
                        toastr["success"]('Formulario excluido com sucesso!');
                    })
                    .catch((err) => toastr["error"]('Não foi possivel excluir a imagem!')) 
                   
            }
        });


    },
    
}));
 