@extends('rpclinica.layout.layout_impressao')
@section('title')
    {{ $relatorio_titulo }}
@endsection
@section('content')
    @include('rpclinica.relatorios.relacao_tabela', ['colunas' => $colunas, 'dados_view' => $dados_view])
@endsection