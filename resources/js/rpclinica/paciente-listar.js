Alpine.data('app', () => ({
    buttonDisabled: false,
    loading: false,
    loadingTela: 'doc', 
    snEdicaoDoc: false,
    loadingDoc: false,
    pacientes: [],
    pacienteSelected: null,
    valuesEstadoCivil: {
        S: 'Solteiro',
        C: 'Casado',
        D: 'Divorciado',
        V: 'Viúvo'
    },
    msg: {
        titulo: '',
        file: '',
        msg: '',
        doc: '',
        celular: '',
        compartilhar: 'ZAP'
    },
    classLabelSituacao: {
        livre: 'label-success',
        agendado: 'label-primary',
        confirmado: 'label-info',
        atendido: 'label-warning',
        bloqueado: 'label-danger'
    },
    loadingConsulta: false,

    // historico
    historicoAgendamentoSelected: null,
    historicoPrintAgendamento: false,

    Anamnese:{
        Documento: '',
        Titulo: '',
        cdDocumento:null,
    }, 
    
    dadosDocumentos: [],
    dadosEnvios: [],
    buttonSalvar: " <i class='fa fa-check'></i>  Salvar ",
    buttonEnviar: "<i class='fa fa-send-o'></i> Enviar ", 
    editorFormulario: null, 
    init() {
        this.pacientes = pacientes;
        

        this.editorFormulario = CKEDITOR.replace('editor-formulario', {
            // Define the toolbar groups as it is a more accessible solution.
            toolbarGroups: [
                {
                    "name": "basicstyles",
                    "groups": ["basicstyles"]
                }, 
                {
                    "name": "undo",
                    "groups": ["Undo","Redo"]
                }, 
                {
                    "name": "paragraph",
                    "groups": ["list", "blocks" ]
                },   
                {
                    "name": "insert",
                    "groups": ["insert"]
                },
                {
                    "name": "styles",
                    "groups": ["styles"]
                } 
            ],
            // Remove the redundant buttons from toolbar groups defined above.
            removeButtons: 'Subscript,Superscript,',
            resize_enabled : false,
            removePlugins: 'elementspath',
            height:['300px'],
            enterMode: CKEDITOR.ENTER_BR, // Define Enter como <br>
            shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>
     
        });

        $('#modeloDocumento').on('select2:select', (evt) => { 
            var idForm = evt.params.data.id;   
            /*
            var filtrado = documentos.filter(function(obj) { return obj.cd_formulario == idForm; });
            if(filtrado[0]){ 
                this.Anamnese.Documento=filtrado[0].conteudo_text; 
                this.Anamnese.Titulo=filtrado[0].nm_formulario; 
            } 
            */
            axios.get(`/rpclinica/json/paciente-doc/${idForm}/${this.pacienteSelected.cd_paciente}`)
                .then((res) => { 
                    this.editorFormulario.setData(res.data.retorno); 
                    this.Anamnese.Documento=res.data.retorno; 
                    this.Anamnese.Titulo=res.data.nm_formulario; 
                })
                .catch((err) => { 
                    toastr['error'](err.response.data.message, 'Erro');
                })
               
        });


        $('#modal-resumo-paciente').on('hidden.bs.modal', () => {
            $('#procedimento').val(null).trigger('change');
            $('#convenio').val(null).trigger('change');
            $('#especialidade').val(null).trigger('change');
        });
    },


    openModal(cdPaciente) {
        this.pacienteSelected = this.pacientes.find((paciente) => paciente.cd_paciente == cdPaciente);
        $('#convenio').val(this.pacienteSelected.cd_categoria).trigger('change');
        $('#cartao').val(this.pacienteSelected.cartao); 
        $('#modal-resumo-paciente').modal('show');
        
    },

    submitConsulta() {
        this.loadingConsulta = true;

        let formConsulta = new FormData(this.$refs.formIniciarConsulta);

        axios.post('/rpclinica/json/paciente-iniciar-consulta', formConsulta)
            .then((res) => location.href = res.data.consulta)
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.loadingConsulta = false);
    },

    openModalHistorico(cdPaciente) {
        this.pacienteSelected = this.pacientes.find((paciente) => paciente.cd_paciente == cdPaciente);

        $('#modal-historico-paciente').modal('show');
    },

    openModalHistoricoAgendamento(cdAgendamento) {
        this.historicoAgendamentoSelected = this.pacienteSelected.agendamentos.find((agendamento) => agendamento.cd_agendamento == cdAgendamento)
        this.historicoPrintAgendamento = true;
    },

    imprimirAnamnese() {
        let url = `/rpclinica/consulta/anamnese/download-pdf/${this.historicoAgendamentoSelected.cd_agendamento}`;

        axios.all([
            axios.get(url, { params: { tipo: 'anamnese' }, responseType: 'blob' }),
            axios.get(url, { params: { tipo: 'exame' }, responseType: 'blob' }),
            axios.get(url, { params: { tipo: 'hipotese' }, responseType: 'blob' }),
            axios.get(url, { params: { tipo: 'conduta' }, responseType: 'blob' })
        ])
            .then(axios.spread((anamnese, exame, hipotese, conduta) => {
                window.open(URL.createObjectURL(anamnese.data), 'anamnese.pdf');
                window.open(URL.createObjectURL(exame.data), 'exame_fisico.pdf');
                window.open(URL.createObjectURL(hipotese.data), 'hipotese_diagnostica.pdf');
                window.open(URL.createObjectURL(conduta.data), 'conduta.pdf');
            }))
            .catch((err) => toastr['error']('Houve um erro ao imprimir.'))
    },

    imprimirDocumento(cdDocumento) {
        axios.get(`/rpclinica/consulta/anamnese/download-pdf/${this.historicoAgendamentoSelected.cd_agendamento}`, {
            params: { tipo: 'documento', cdDocumento },
            responseType: 'blob'
        })
            .then((res) => {
                window.open(URL.createObjectURL(res.data), 'documento.pdf');
            })
            .catch((err) => toastr['error']('Houve um erro ao imprimir o documento!'));
    },

    /* Documento */
    openModalDocumento(dadosPaciente) {
        this.loadingTela='doc';
        this.pacienteSelected = dadosPaciente;
        this.Anamnese.Documento=null; 
        this.Anamnese.Titulo=null; 
        this.snEdicaoDoc=false;
        $('#modeloDocumento').val(null).trigger('change'); 
       // $('#documentoPaciente').modal('show');
       console.log(dadosPaciente);
        axios.get(`/rpclinica/json/getDocumentoPacinete/${dadosPaciente.cd_paciente}`)
            .then((res) => {
                this.editorFormulario.setData(''); 
                this.dadosDocumentos = res.data.documento; 
                this.dadosEnvios = res.data.envio;
            })
            .catch((err) => toastr['error']('Houve um erro ao carregar o documento!'));
    },

    storeDocumento() { 
        this.loadingDoc=true;
        this.loading = true;
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_DOCUMENTO'));

        var conteudoAnamnese = (this.editorFormulario?.getData()) ? this.editorFormulario?.getData() : '';
        form.set('documento', conteudoAnamnese); 
        this.Anamnese.Documento= conteudoAnamnese;

        axios.post(`/rpclinica/json/storeDocumentoPacinete/${this.pacienteSelected.cd_paciente}`, form)
            .then((res) => {  
                
                console.log(res.data); 
                this.dadosDocumentos = res.data.documento; 
                $('#modeloDocumento').val(null).trigger('change');
                this.editorFormulario?.setData(""); 
                this.Anamnese.Titulo = "";
                this.Anamnese.cdDocumento="";
                this.Anamnese.Documento=""; 
                toastr['success'](res.data.message);

            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.loadingDoc=false;
                this.buttonDisabled = false;
                this.Anamnese.Documento = null; 
                this.Anamnese.cdDocumento = null; 
                this.snEdicaoDoc=false;
                this.buttonSalvar = " <i class='fa fa-check'></i>  Salvar ";
            });
    }, 

    storeMsg() { 
        this.loadingDoc=true;
        this.loading = true;
        this.buttonEnviar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Enviando... ";
        this.buttonDisabled = true; 
        axios.post(`/rpclinica/json/imprimirDocumentoPaciente/${this.msg.doc}`, this.msg)
            .then((res) => {  
                
                console.log(res.data);
          
               if(res.data.dados.status=='200'){
                    toastr['success']('Mensagem enviada com sucesso!');
                    this.loadingTela='doc';
                    this.msg.doc='';
                    this.msg.titulo='';
                    this.msg.file='';
                    this.msg.msg='';
                    this.msg.celular='';
                    this.dadosEnvios = res.data.envio;
               }else{
                toastr['error'](res.data.dados.message, 'Erro'); 
               }

            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.loadingDoc=false;
                this.buttonDisabled = false;
                this.Anamnese.Documento = null; 
                this.Anamnese.cdDocumento = null; 
                this.snEdicaoDoc=false;
                this.buttonEnviar = " <i class='fa fa-send-o'></i> Enviar ";
            });
    }, 

    editDocumento(documento) {  
        this.snEdicaoDoc=true;
        console.log(documento);
        $('#modeloDocumento').val(documento.cd_formulario).trigger('change'); 
        this.Anamnese.Documento =documento.conteudo;
        this.Anamnese.cdDocumento =documento.cd_documento_paciente; 
        this.Anamnese.Titulo =documento.titulo; 
        this.editorFormulario?.setData(documento.conteudo);  

    },

    cancelarEdicao(){
        $('#modeloDocumento').val(null).trigger('change'); 
        this.Anamnese.Documento =null;
        this.Anamnese.cdDocumento =null; 
        this.Anamnese.Titulo =null; 
        this.snEdicaoDoc=false;
    },

    excluirDocumento(idDocumento) {

        Swal.fire({
           title: 'Confirmação',
           html: "<h4 style='font-weight: 400;font-style: italic;'>Deseja Excluir esse Documento?</h4>",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#22baa0',
           cancelButtonColor: '#d33',
           cancelButtonText: 'Não',
           confirmButtonText: 'Sim'
       }).then((result) => {
           if (result.isConfirmed) {  
               this.loading = true;
               this.loadingDoc=true;
               axios.delete(`/rpclinica/json/deleteDocumentoPac/${this.pacienteSelected.cd_paciente}/${idDocumento}`)
                   .then((res) => {
                       this.dadosDocumentos = res.data.documento;
                       toastr['success']('Documento deletado com sucesso!'); 
                   })
                   .catch((err) => toastr['error'](err.response.data.message))
                   .finally(() => {
                       this.loading = false;
                       this.loadingDoc=false;
                       this.deleteDocIndice = null
                   });
           }
       });
    },


    teste(dados) {
        console.log(dados);
        this.loadingTela='msg';
        this.msg.titulo=dados.titulo;
        this.msg.msg='';
        this.msg.celular=this.pacienteSelected.celular;
        this.msg.doc=dados.cd_documento_paciente;   

        /*
        Swal.fire({
            title: "<strong>HTML <u>example</u></strong>",
            icon: "info",
            html: `
              You can use <b>bold text</b>,
              <a href="#" autofocus>links</a>,
              and other HTML tags
            `,
            showCloseButton: true,
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText: `
              <i class="fa fa-thumbs-up"></i> Great!
            `,
            confirmButtonAriaLabel: "Thumbs up, great!",
            cancelButtonText: `
              <i class="fa fa-thumbs-down"></i>
            `,
            cancelButtonAriaLabel: "Thumbs down"
        });
        */
    },
  
}));

$(document).ready(() => {
    $('#modal-resumo-paciente,#modal-historico-paciente').modal({
        backdrop: 'static',
        show: false
    })
})

