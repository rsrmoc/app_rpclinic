 
Alpine.data('appFormulario', () => ({
   
    empresa: empresa,

    async deleteImgPesquisa() {
       
         
        Swal.fire({
            title: 'Envio de Mensagem',
            text: "Tem certeza que deseja enviar os agendamentos selecionados?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
        .then((result) => {
            if (result.isConfirmed) { 
                console.log(empresa);
               
                axios.delete(`/rpclinica/empresa-delete-img-pesq/${empresa}` )
                .then((res) => {  
                    location.href = "/rpclinica/empresa-listar";
                })
                .catch((err) => toastr['error'](err.response.data.message)); 
              
            }
        });
    
    },
 
}));

 

 

 
