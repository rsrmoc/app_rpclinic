<?php

namespace App\Bibliotecas;

use setasign\Fpdi\Tcpdf\Fpdi;

class PDFSign extends FPDI
{
    private $nome;
    private $cpf;

    function __construct($nome, $cpf)
    {
        parent::__construct();
        $this->nome = strtoupper($nome);
        $this->cpf = $cpf;
    }

    function Header()
    {
    }

    function Footer()
    {
        // $this->SetY(-10);
        // $this->SetFont('Helvetica', '', 6);

        // $textoFooter = "DOCUMENTO ASSINADO DIGITALMENTE POR $this->nome COM O CPF $this->cpf, VERIFIQUE O DOCUMENTO EM https://verificador.iti.br/";
        // $this->Cell(0, 10, $textoFooter, 'T', 0, 'C');
    }
}
