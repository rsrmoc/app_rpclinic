import axios from 'axios';
import moment from 'moment';
 
Alpine.data('app', () => ({
    atendPaciente : atendPaciente,
    listaExame: listaExame,
    loading: false, 
    atendimento: null,
    paciente: null,
    cpf: null,
    nasc: null,
    dt_atend: dt_atual,            
    SituacaoWhast: null,
    foneWhast: null,
    classWhast: 'fa fa-whatsapp  ',
    dataCelular: null,
    div_item: false,  
    ExamesItens: [],
    formExame: {
        exame: null,
        nm_exame: '',
        olho: null,
        nm_olho: '',
        obs: ''
    },
    autoIncremento: 1000, 

    init() { 
        
        $('#cd_exame').on('select2:select', (evt) => { 
            this.formExame.exame = evt.params.data.id; 
            this.formExame.nm_exame = evt.params.data.text; 
        });
          
        $('#olho').on('select2:select', (evt) => { 
            this.formExame.olho = evt.params.data.id; 
            this.formExame.nm_olho = evt.params.data.text; 
        });

    },

    clearExame(){
        this.formExame.exame='';
        this.formExame.nm_exame='';
        this.formExame.olho='';
        this.formExame.nm_olho='';
        this.formExame.obs='';
    },

    add(){
      
        var tabela = document.getElementById('tabela');
        var exame = this.formExame.exame; 
        var nm_exame = this.formExame.nm_exame;
        var olho = this.formExame.olho;
        var nm_olho = this.formExame.nm_olho;
        var obs = this.formExame.obs;

        if(!exame){
            toastr['error']("Exame não informado!");
            return false;
        }

        if(!olho){
            toastr['error']("Olho não informado!");
            return false;
        }

        if(this.listaExame.includes(parseInt(exame))){
            toastr['error']("Exame já esta cadastrado!");
            return false;
        } 
        this.listaExame.push(parseInt(exame));


        this.autoIncremento++;

        var novo_item = '<div class="row" id="'+this.autoIncremento+'">'
            +'<div class="col-md-4 form-group "><input type="hidden" name="cd_item[]" value=""><input type="hidden" name="cd_exame[]" value="'+ exame +'"><span class="form-control"  style="background-color: #f9f9f9;">'+nm_exame+' </span></div>'
            +'<div class="col-md-2 form-group "><input type="hidden" name="olho[]" value="'+ olho +'"><span class="form-control" style="background-color: #f9f9f9;" >'+nm_olho+'</span></div>'
            +'<div class="col-md-5 form-group "><input type="hidden" name="obs[]" value="'+ obs +'"><span class="form-control"  style="background-color: #f9f9f9;">'+obs+'</span></div>'
            +'<div class="botao col-md-1 ">'
            +'<button type="button" class="btn btn-default" style="color: #a94442; background-color: #f3f2f2;" x-on:click="remover('+this.autoIncremento+')" ><i class="fa fa-close "></i></button>'
            +'</div>'
        +'</div>';
        
        tabela.innerHTML += novo_item;

        $('#cd_exame').val(null).trigger('change');
        $('#olho').val(null).trigger('change'); 
        toastr['success']("Exame Incluido com sucesso!");  
        this.clearExame(); 
  
    },
  
    remover(e) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse exame?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22baa0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(e).outerHTML = ''    ; 
            }
        });
    },

    getAtendimento(){
        
        if(this.atendimento){

            axios.get(`/rpclinica/json/atendimento-atend/${this.atendimento}`)
            .then((res) => {
                 
                this.loading = (res.data.retorno) ? true : false;
                this.paciente= (res.data.retorno) ? res.data.retorno?.paciente.nm_paciente : null;
                this.cpf= (res.data.retorno) ? res.data.retorno?.paciente.cpf : null;
                this.nasc= (res.data.retorno) ?  res.data.retorno?.paciente.dt_nasc : null;
                this.dt_atend= (res.data.retorno) ?  res.data.retorno?.dt_agenda : null;
              
            })
            .catch((err) => {
                toastr['error'](err.response.data.message);
            })

        }

    },

    validarZap(){

        const element = document.querySelector('#Zap');
        element.classList.remove('whastValido');
        element.classList.remove('whastInvalido');
        element.classList.remove('whastNeutro');
        this.dataCelular= $('#agendamento-celular').val();
        this.classWhast= 'fa fa-whatsapp  ';
        this.SituacaoWhast = null;
        this.foneWhast = null;
        if(!this.dataCelular) {
            return toastr['error']("Informação Incompleta, Numero não Informado!");
        } 
        axios.get(`/rpclinica/comunicacao-check/${this.dataCelular}`)
        .then((res) => {
            console.log(res.data);
            if(res.data.retorno==false) {
                
                toastr['error'](res.data.msg); 
                if(res.data.dados==false) {
                     
                    this.classWhast = "fa fa-whatsapp whastInvalido";
                    this.SituacaoWhast=false;
                    this.foneWhast = this.dataCelular;
                }
            }
            if(res.data.retorno==true) {
                toastr['success'](res.data.msg); 
                this.classWhast = "fa fa-whatsapp whastValido";
                this.SituacaoWhast=true;
                this.foneWhast = this.dataCelular;
            } 
          
     
        })
        .catch((err) => {
            toastr['error'](err.response.data.message);
        });
        

    },
  
 
}));

$(document).ready(function () {

    $('#agendamento-paciente').select2({
        ajax: {
            url: '/rpclinica/json/pacientes',
            dataType: 'json',
            processResults: (data) => {
                let search = $('#agendamento-paciente').data('select2').results.lastParams?.term;

                if (search) {
                    data.unshift({
                        id: search,
                        text: `"${search}" Novo paciente`
                    });

                }

                return {
                    results: data
                };
            }
        }
    });
 
    $('#agendamento-paciente').on('select2:select', (evt) => {
        let cdPaciente = evt.params.data.id;
        console.log(evt.params.data);
        if (typeof cdPaciente == 'number') {
            this.loadingPaciente = true;
            axios.get(`/rpclinica/json/paciente`, { params: { cd_paciente: cdPaciente } })
                .then((response) => {
                    this.dadosPaciente = response.data;
                    console.log(this.dadosPaciente); 
                    $('#nasc').val(response.data.dt_nasc);
                    $('#cpf').val(response.data.cpf); 
                })
                .catch((error) => toastr['error'](error.response.data.message))
                .finally(() => this.loadingPaciente = false);
        } else { 
            $('#nasc').val('');
            $('#cpf').val('');  
        }

    });
 
  
});