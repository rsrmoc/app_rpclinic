import axios from 'axios';
import moment from 'moment';

Alpine.data('app', () => ({
    loading: false,
    retornoLista: null,
    paginatedData: [],
    currentPage: 1,
    itemsPerPage: 10,
    totalPages: 0,
    index: 0,
    loadingPesq: false,
    textoPadrao: null,
    buttonPesquisar: "  <span class='glyphicon glyphicon-search' aria-hidden='true'></span> ",
    buttonSalvarLaudo: '<i class="fa fa-check"></i> Salvar',
    buttonSalvarAnot: '<i class="fa fa-check"></i> Salvar',
    buttonSalvarTemp: '<i class="fa fa-check"></i> Salvar',
    infoModal: {},
    indexModal: null,
    editor: null,
    selectedTextoPadrao: '',  // New variable for the selected value
    isChecked: true,

    classSituacaoExame: {
        A: 'btn-rss', 
        E: 'btn-info',
        R: 'btn-success',
        '': ''
    },

    situacaoExame: {
        A: '<i class="fa fa-ban" style="padding-left:2px;"></i> Aguardando',
        E: '<i class="fa fa-check" style="padding-left:2px;"></i> Executado',
        R: '<i class="fa fa-check-square-o" style="padding-left:2px;"></i> Realizado', 
        '': ''
    },


    swalWithBootstrapButtons: Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success swal-button",
            cancelButton: "btn btn-danger swal-button",
            input: "form-control"
        },
        buttonsStyling: false
    }),

    init() {
 
        this.getPage();

        const editor = CKEDITOR.replace('conteudo', {
            toolbar: [{
                name: 'basicstyles',
                items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat']
            },
            {
                name: 'paragraph',
                items: ['NumberedList', 'BulletedList', '-', 'Blockquote']
            },
            {
                name: 'styles',
                items: ['Format']
            }]
        });

        this.editor = editor;

        this.editor.setData(this.infoModal.conteudo_laudo);

        this.editor.on('change', () => {
            const data = editor.getData(); 
        });

        // Use an arrow function or save the context to avoid issues with 'this'
        $('#texto_padrao').on('change', (event) => {
            const selectedValue = event.target.value;  // Get the selected value

            // Set the editor data based on the selected value
            console.log('Selected Texto Padrão:', selectedValue);
            this.editor.setData(selectedValue);
            console.log(this.editor);
        }); 
 
    },

    paginateData() {
        const startIndex = (this.currentPage - 1) * this.itemsPerPage;
        const endIndex = startIndex + this.itemsPerPage;
        this.paginatedData = this.retornoLista.slice(startIndex, endIndex);
    },

    goToPage(page) {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
            this.paginateData();
        }
    },

    nextPage() {
        if (this.currentPage < this.totalPages) {
            this.currentPage++;
            this.paginateData();
        }
    },

    previousPage() {
        if (this.currentPage > 1) {
            this.currentPage--;
            this.paginateData();
        }
    },

    getPage() {
        this.loadingPesq = true;
        this.buttonPesquisar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i>  ";
        let form = new FormData(document.querySelector('#form-parametros'));
        axios.post(`/rpclinica/json/cirurgias`, form)
            .then((res) => {
                this.retornoLista = res.data.query 
                this.totalPages = Math.ceil(this.retornoLista.length / this.itemsPerPage);
                this.paginateData();
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })

            .finally(() => {
                this.buttonPesquisar = "  <span class='glyphicon glyphicon-search' aria-hidden='true'></span> ";
                this.loadingPesq = false;
            });

    },

    clickModal(dados,index) { 
        this.indexModal = index;
        this.infoModal = dados; 

        if(dados.sn_laudo == true){
            this.infoModal.sn_laudo=true
        }else{
            this.infoModal.sn_laudo=false
        } 

        if(dados.sn_laudo==true){
            $('#label_laudo_checkbox span').addClass('checked'); 
            $('#label_laudo_checkbox input').prop('checked', true); 
        }else{ 
            $('#label_laudo_checkbox span').removeClass('checked');
            $('#label_laudo_checkbox input').prop('checked', false);
        }
 
        this.editor.setData((this.infoModal.conteudo_laudo) ? this.infoModal.conteudo_laudo : '')
        axios.post(`/rpclinica/json/modal-central-laudos`, dados)
            .then((res) => { 
                this.textoPadrao = res.data.texto_padrao;
                this.infoModal.array_img = res.data.array_img 
                this.infoModal.notes = res.data.hist;
                console.log(res.data);
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })

            .finally(() => {
                $('.modalDetalhes').modal('toggle');
            });

        
 
    },

    addHistory() {
 
        this.buttonSalvarAnot= " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        let form = new FormData(document.querySelector('#form_RESERVA_CIRURGIA'));
        form.append('cd_agendamento_item', this.infoModal.cd_agendamento_item)
        axios.post(`/rpclinica/json/central-laudos/addHist`, form)
            .then((res) => {
                axios.get(`/rpclinica/json/central-laudos/getHist/${this.infoModal.cd_agendamento_item}`)
                    .then((res) => {
                        this.infoModal.notes = res.data;
                        document.getElementById("form_RESERVA_CIRURGIA").reset();
                    })
                    .catch((err) => {
                        toastr['error'](err.response.data.message, 'Erro');
                    });
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => { 
                this.buttonSalvarAnot= this.buttonSalvarTemp;
            });
    },

    submitLaudo() {
        this.buttonSalvarLaudo = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        const dados = { 'conteudo_laudo': this.editor.getData() } 
        axios.post(`/rpclinica/json/central-laudos/saveLaudo/${this.infoModal.cd_agendamento_item}`, dados)
            .then((res) => {
                 
                this.infoModal.conteudo_laudo = this.editor.getData(); 
                this.getPage();
                this.infoModal.situacao = 'E';
                toastr['success']('Laudo atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })

            .finally(() => {
                this.buttonSalvarLaudo = this.buttonSalvarTemp;
            });
    },

    liberarLaudo() { 

        this.swalWithBootstrapButtons.fire({
            title: 'Confirmação',
            html: "<h4 style='font-weight: 500;font-style: italic;'>Deseja liberar o laudo?</h4>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
 
                if ($('#sn_laudo_checkbox').is(":checked")) {
                    this.infoModal.sn_laudo = true;
                }else{
                    this.infoModal.sn_laudo = false;
                }
  
                axios.post(`/rpclinica/json/central-laudos/liberarLaudo/${this.infoModal.cd_agendamento_item}`, this.infoModal)
                    .then((res) => {  
                        this.getPage(); 

                        if(this.infoModal.sn_laudo == true){
                            toastr['success']('Laudo liberado com sucesso!');
                            this.infoModal.situacao = 'R';
                        }else{
                            toastr['success']('Laudo cancelado com sucesso!'); 
                            this.infoModal.situacao = 'E';
                        }
                        
                    })
                    .catch((err) => {
                        toastr['error'](err.response.data.message, 'Erro');
                    })
                    .finally(() => { 
                    });

            }
        });


    }
}));
