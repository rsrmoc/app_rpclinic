import axios from 'axios';
import moment from 'moment';

Alpine.data('appAnamnese', () => ({
 

    buttonSalvar: " Salvar ",
    loadHistorico: false,
    dadosHistorico: null,
    dadosAnamnese :{
        Motivo : null,
        Historia : null,
        Medicamentos : null,
        Alergias : null,
        Conduta : null,
        DataAnamnese : null,
        Profissional : null,
    },

    init() {
        this.getAnanmnese();
    },

    getAnanmnese(){
        
        this.loadHistorico = true; 
        axios.get(`/rpclinica/json/anamnese/${idAgendamento}`)
        .then((res) => { 
            this.dadosAnamnese.DataAnamnese = (res.data.agendamento.dt_anamnese) ? res.data.agendamento.dt_anamnese : res.data.data_atual;
            this.dadosAnamnese.Profissional = res.data.agendamento.profissional.nm_profissional;
            this.dadosAnamnese.Motivo = res.data.agendamento.motivo_consulta;
            this.dadosAnamnese.Medicamentos = res.data.agendamento.medicamentos;
            this.dadosAnamnese.Historia = res.data.agendamento.hist_oft;
            this.dadosAnamnese.Alergias = res.data.agendamento.alergias;
            this.dadosAnamnese.Conduta = res.data.agendamento.conduta;
            this.dadosHistorico = res.data.hist;
        })
        .catch((err) => { 
            toastr['error'](err.response.data.message);
        })
        .finally(() => {
            this.loadHistorico = false
        });

    },

    getSalvar(){
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";  
        let form = new FormData(document.querySelector('#form-anamnese')); 
        axios.post(`/rpclinica/json/anamnese/${idAgendamento}`, form)
        .then((res) => { 
            toastr['success']('Paciente atualizado com sucesso!');  
        })
        .catch((err) => {  
            toastr['error'](err.response.data.message,'Erro'); 
        })

        .finally(() => {
            this.buttonSalvar = " Salvar "; 
        });
    }
 
}));
 

