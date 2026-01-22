<div role="tabpanel " style="">
    <!-- Nav tabs -->
    <ul class="nav nav-pills nav-justified panel glass-panel" role="tablist" style=" margin-bottom: 15px; ">
        <li role="presentation" class="active" ><a href="#TabCalendar" role="tab" data-toggle="tab" aria-expanded="false"> Agenda</a></li> 
        <li role="presentation"><a  href="#TabAgendaAvancada" role="tab" data-toggle="tab" aria-expanded="true">Pesquisar Avançada</a></li> 
        <li role="presentation"><a  href="#TabAgendaConfirmacao"  role="tab" data-toggle="tab" aria-expanded="true" >Confirmação</a></li> 
    </ul> 
</div>

<style>
 
    .panel-body-livre {
        box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
        transition: box-shadow 0.2s ease-in-out;
        border-top: 3px solid #2ecc71;
    }

    .info-box .color-livre i {
        color: #2ecc71;
    }

    .whastValido {
        color: #2ecc71;
    }

    .whastInvalido {
        color: #ee6414;
    }

    .whastNeutro {
        color: #333;

    }

    .panel-body-agendado {
        box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
        transition: box-shadow 0.2s ease-in-out;
        border-top: 3px solid #7a6fbe;
    }

    .info-box .color-agendado i {
        color: #7a6fbe;
    }

    .panel-body-confirmado {
        box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
        transition: box-shadow 0.2s ease-in-out;
        border-top: 3px solid #12AFCB;
    }

    .info-box .color-confirmado i {
        color: #12AFCB;
    }

    .panel-body-aguardando {
        box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
        transition: box-shadow 0.2s ease-in-out;
        border-top: 3px solid #FF9800;
    }

    .info-box .color-aguardando i {
        color: #FF9800;
    }

    .panel-body-atendido {
        box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
        transition: box-shadow 0.2s ease-in-out;
        border-top: 3px solid #f6d433;
    }

    .info-box .color-atendido i {
        color: #f6d433;
    }

    .panel-body-cancelado {
        box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
        transition: box-shadow 0.2s ease-in-out;
        border-top: 3px solid #f25656;
    }

    .info-box .color-cancelado i {
        color: #f25656;
    }

    .btn-verde{
        color: #20c94e;
        font-weight: 600;
        font-size: 1.4em;
    }

    .btn-roxo{
        color: #7a6fbe;
        font-weight: 500;
        font-size: 1.4em;
    }
    .btn-azul{
        color: #12AFCB;
        font-weight: 500;
        font-size: 1.4em;
    }
    .btn-laranja{
        color: #f2ca4c;
        font-weight: 500;
        font-size: 1.3em;
    }

    .btn-vermelho{
        color: #fd0f76;
        font-weight: 500;
        font-size: 1.3em;
    }


    .label-black {
        background: #34425a;
    }


    .ModalAgendamento .modal {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        overflow: hidden;
    }

    .ModalAgendamento .modal-dialog {
        position: fixed;
        margin: 0;
        width: 100%;
        height: 100%;
        padding: 0;
    }

    .ModalAgendamento .modal-content {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        border: 1px solid #ddd;
        border-radius: 0;
        box-shadow: none;
    }

    .ModalAgendamento .modal-header {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 60px;
        padding: 10px;
        background: #f1f3f5;
        border: 0;
    }

    .ModalAgendamento .modal-title {
        font-weight: 300;
        font-size: 2em;
        color: #444444;
        line-height: 30px;
    }

    .ModalAgendamento .modal-body {
        position: absolute;
        top: 50px;
        bottom: 0px;
        width: 100%;
        overflow: auto;
        background: #f1f3f5;
    }

    .ModalAgendamento .modal-footer {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        /*padding: 2px;*/
        background: #f1f3f5;
    }

    .ModalAgendamento .form-group {
        margin-bottom: 10px;
    }

    .form-control[disabled],
    .form-control[readonly],
    fieldset[disabled] .form-control {
        background-color: #f1f3f5;
        opacity: 1;
    }

    /* Dia Atual */
    .fc-today {
        background: rgba(255, 255, 255, 0.05) !important;
        border: none !important;
        border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
        font-weight: bold;
    }

    .fixed-header .header{
        left:0;
        position:sticky;
        right:0;
        top:0;
        }
        .fc .fc-scrollgrid-section-header.fc-scrollgrid-section-sticky>* {
            top: 0;
        }


    .box-btn-float {
        position: fixed;
        bottom: 2em;
        right: 2em;
        z-index: 999;
    }

    .text-aguardando {
        color: #FF9800;
    }

    .box-btn-float button {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 3.5em;
        width: 3.5em;
        background-color: #22baa0;
        color: #ffffff;
        border: none;
        border-radius: 100%;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }

    .box-btn-float button i {
        margin: 0;
        font-size: 2rem;
    }

    .box-btn-float button:hover,
    .box-btn-float button:focus {
        background-color: #1be1bf;
        transform: translateX(0px) scale(1.1);
        transition: transform 0.3s;

    }

    .text-success {
        font-weight: bold;
    }

    #calendar .datepicker.datepicker-inline {
        width: 100%;
    }

    #calendar .datepicker.datepicker-inline table {
        margin: 0 auto;
        width: 100%;
    }

    .select2-container.select2-container--default.select2-container--open,
    .swal2-container {
        z-index: 9999;
    }

    #cadastro-consulta .tabpanel .nav.nav-tabs li {

        text-align: center;
    }

    #cadastro-consulta .tabpanel .nav.nav-tabs li.active {
        font-weight: bold;
    }

    .fc-icon.fc-icon-fa {
        font-family: "FontAwesome" !important;
    }

    .btnAgendado{
        color:#7a6fbe; font-weight: bold;padding: 4px 10px;
    }

    .btnConfirmado{
        color:#12AFCB; font-weight: bold;padding: 4px 10px;
    }

    .btnAguardando{
        color:#FF9800; font-weight: bold;padding: 4px 10px;
    }

    .btnCancelado{
        color:#ea0033; font-weight: bold;padding: 4px 10px;
    }

    .btnFaltou{
        color:#ea0033; font-weight: bold;padding: 4px 10px;
    }

    .btnAtendido{
        color:#f6d434; font-weight: bold;padding: 4px 10px;
    }

    .btnAtendimento{
        color:#20c94e; font-weight: bold;padding: 4px 10px;
    }
    .fc-time{
        font-style: italic;
        color: #cbd5e1;
    }

    /* Removido conflito de altura com o slot-height */
    /* #calendar > div.fc-view-container > div > table > tbody > tr > td > div.fc-scroller.fc-time-grid-container > div > div.fc-slats > table > tbody > tr {
        height: 30px;
    } */

    .table-responsive .nav-tabs>li>a {
       margin-right: 0px;
    }

    .table-responsive .nav-tabs>li>a {
        border-bottom: 1px solid #fff;
    }

    .table-responsive .page-title {
        padding: 0px;
        background: #fff;
        border-bottom: 1px solid #fff;
    }

    .nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover {
        color: #2dd4bf;
        background-color: rgba(45, 212, 191, 0.1);
        font-weight: 700
    }
   
    .nav>li>a:focus, .nav>li>a:hover {
        text-decoration: none;
        background-color: rgba(255, 255, 255, 0.05); 
        color: #cbd5e1;
    }
    .fc-time > span {
        font-size: 1.2em;
    }

    .fc-center > h2 {
        min-height: 2.0em;
        height: auto;
        padding: 5px 20px;
        font-size: 1.2em;
        line-height: 1.4;
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        font-weight: 600;
        color: #cbd5e1;
        margin: 0 !important;
    }
 
    
</style> 