import axios from 'axios';
import moment from 'moment';

Alpine.data('appReceita', () => ({
    Receitas: [],
    loadReceita: false,
    init() {
        this.getReceitas();
    },

    getReceitas(){
         
        this.loadHistorico = true; 
        axios.get(`/rpclinica/json/receitas/${idAgendamento}`)
        .then((res) => {
            console.log(res.data);
            this.Receitas  = res.data.receitas
        })
        .catch((err) => {  
            toastr['error'](err.response.data.message,'Erro'); 
        })

        .finally(() => {
            this.loadHistorico = false; 
        });
    }

 
 
}));
 

