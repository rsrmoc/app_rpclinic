  <form x-on:submit.prevent="salvarLancamento" class="panel panel-white">
      @csrf
      <input type="hidden" name="data" x-model="parametros.data">
      <input type="hidden" name="profissional" x-model="parametros.prof">
      <div class="panel-body">
          <h4 class="modal-title" style="font-size: 1.7em; text-align: center;">
              Recebimento da Conta</h4>
          <br>
 

          <div class="row" x-show="modalCompleto.situacao_conta=='F'">


              <div class="row">
                  <div class="col-md-3 col-sm-6 col-xs-12">
                      <div class="form-group">
                          <div class="mat-div">
                              <label>Conta <span class="red normal">*</span> </label>
                              <select name="conta" class="  form-control " tabindex="-1" style="width: 100%" required
                                  aria-hidden="true" id="lancamentosConta">
                                  <option value="">Selecione</option>
                                  @foreach ($contasBancaria as $conta)
                                      <option value="{{ $conta->cd_conta }}" data-tp="{{ $conta->tp_conta }}">
                                          {{ $conta->nm_conta }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                  </div>

                  <div class="col-md-3 col-sm-6 col-xs-12">
                      <div class="form-group">
                          <div class="mat-div">
                              <label>Forma de Pagamento <span class="red normal">*</span> </label>
                              <select name="forma" class="  form-control " tabindex="-1" style="width: 100%" required
                                  aria-hidden="true" id="lancamentosFormaPagamento">
                                  <option value="">Selecione</option>
                                  @foreach ($formasPagamento as $forma)
                                      <option value="{{ $forma->cd_forma_pag }}">
                                          {{ $forma->nm_forma_pag }}</option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                  </div>

                  <div class="col-md-2 col-sm-4 col-xs-12 ">
                      <label>Data do Recebimento <span class="red normal">*</span></label>
                      <input type="date" class="form-control required" value="" name="data_pagamento"
                          maxlength="100" aria-required="true" required placeholder="Descrição"
                          x-model="inputsLancamento.data_pagamento">
                  </div>

                  <div class="col-md-2 col-sm-4 col-xs-12 ">
                      <label>Valor do Recebimento <span class="red normal">*</span></label>
                      <input class="form-control   required" required x-mask:dynamic="$money($input, ',')"
                          value="" name="valor_pago" maxlength="100" aria-required="true"
                          x-model="inputsLancamento.valor_pago" />
                  </div>

                  <div class="col-md-2 col-sm-4 col-xs-12">
                      <label>Parcelas <span class="red normal">*</span></label>
                      <select name="parcelas" class=" form-control select2" style="width: 100%" required
                          id="numeroParcelas"> 
                          @foreach (range(1, 36) as $val)
                              <option value="{{ $val }}">
                                  {{ $val == 1 ? $val . ' Parcela' : $val . ' Parcelas' }}</option>
                          @endforeach
                      </select>
                  </div>

              </div>


              <hr>
              <template x-for="parcela, index in inputsLancamento.parcelas">
                  <div>
                      <template x-if="index==0">
                          <div class="row" style="margin-top: 12px;">
                              <div class="col-md-1 col-sm-2 col-xs-12 " style="padding-right:2px; padding-left: 2px;">
                                  <label class="bold">Parcela <span class="red normal">*</span> </label>
                              </div>
                              <div class="col-md-3 col-sm-6 col-xs-12 " style="padding-right:2px; padding-left: 2px;">
                                  <label class="bold">Descrição <span class="red normal">*</span> </label>
                              </div>
                              <div class="col-md-1 col-sm-2 col-xs-12 " style="padding-right:2px; padding-left: 2px;">
                                  <label class="bold">Documento <span class="red normal">*</span> </label>
                              </div>
                              <div class="col-md-6 col-sm-10 col-xs-12 " style="padding:0px; ">

                                  <div class="col-md-3 col-sm-2 col-xs-12 "
                                      style="padding-right:2px; padding-left: 2px;"> <label class="bold">Data do
                                          Vencimento </label> <span class="red normal">*</span></div>
                                  <div class="col-md-3 col-sm-2 col-xs-12 "
                                      style="padding-right:2px; padding-left: 2px;"> <label class="bold">Valor
                                          Parcela <span class="red normal">*</span> </label> </div>
                                  <div class="col-md-3 col-sm-2 col-xs-12 "
                                      style="padding-right:2px; padding-left: 2px;"> <label class="bold">Data do
                                          Recebimento <span class="red normal">*</span> </label> </div>
                                  <div class="col-md-3 col-sm-2 col-xs-12 "
                                      style="padding-right:2px; padding-left: 2px;"> <label class="bold">Valor do
                                          Recebimento <span class="red normal">*</span> </label> </div>

                              </div>
                          </div>
                      </template>
                      <div class="row" style="margin-top: 12px;">
                          <div class="col-md-1 col-sm-2 col-xs-12  " style="padding-right:2px; padding-left: 2px;">
                              <div class="form-control disabled" style="text-align: center; font-weight: 900;"
                                  x-text="index + 1">
                              </div>
                          </div>
                          <div class="col-md-3 col-sm-6 col-xs-12 "style="padding-right:2px; padding-left: 2px;">
                              <input type="text" class="form-control required" required="" value=""
                                  name="parcelas[][descricao]" maxlength="100" aria-required="true"
                                  placeholder="Descrição" x-model="parcela.descricao">
                          </div>
                          <div class="col-md-1 col-sm-2 col-xs-12 "style="padding-right:2px; padding-left: 2px;">
                              <input type="text" class="form-control required" required="" value=""
                                  name="parcelas[][documento]" maxlength="100" aria-required="true"
                                  placeholder="Doc." x-model="parcela.documento">
                          </div>
                          <div class="col-md-6 col-sm-10 col-xs-12 " style="padding:0px; ">

                              <div class="col-md-3 col-sm-2 col-xs-12 "style="padding-right:2px; padding-left: 2px;">
                                  <input type="date" class="form-control required" required="" value=""
                                      name="parcelas[][data_vencimento]" maxlength="100" aria-required="true"
                                      placeholder="Descrição" x-model="parcela.data_vencimento">
                              </div>
                              <div class="col-md-3 col-sm-2 col-xs-12 "style="padding-right:2px; padding-left: 2px;">
                                  <input onkeypress="return(moeda(this,'.',',',event))" class="form-control required"
                                      required="" value="" name="parcelas[][valor]" maxlength="100"
                                      aria-required="true" placeholder="Valor Parcela" x-model="parcela.valor" />
                              </div>
                              <div class="col-md-3 col-sm-2 col-xs-12 " style="padding-right:2px; padding-left: 2px;">
                                  <input type="date" class="form-control required" value="" required=""
                                      name="parcelas[][data_pagamento]" maxlength="100" aria-required="true"
                                      placeholder="Descrição" x-model="parcela.data_pagamento">
                              </div>
                              <div class="col-md-3 col-sm-2 col-xs-12 " style="padding-right:2px; padding-left: 2px;">
                                  <input onkeypress="return(moeda(this,'.',',',event))" class="form-control required"
                                      value="" name="parcelas[][valor_pago]" maxlength="100"
                                      aria-required="true" required="" placeholder="Valor Recebido"
                                      x-model="parcela.valor_pago">
                              </div>

                          </div>
                          <div class="col-md-1 col-sm-2 col-xs-12 "style="padding-right:2px; padding-left: 2px;">
                              <label class="panel-title center red " x-on:click="excluirParcela(index)">
                                  <i style="font-weight: 400; font-size: 22px; padding-top: 5px; cursor: pointer"
                                      class="fa fa-trash-o"></i>
                              </label>
                          </div>
                      </div>
                  </div>

              </template>

              <div class="box-footer" style="margin-top: 15px; margin-bottom: 20px;">
                <div class="row" >
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <button type="submit" class="btn btn-success"
                            style="display: inline-flex; align-items: center; gap: 10px" x-bind:disabled="loading">
                            Salvar
                            <template x-if="loading">
                                <div class="loading loading-sm"></div>
                            </template>
                        </button> 
                        <input type="reset" class="btn btn-default" value="Limpar">
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12" style="text-align: right">
                        <button type="button" class="btn btn-default btn-rounded" 
                            x-show="(modalCompleto.recebido == 'N')"
                            x-on:click="lancarDesconto"
                            style="color: #b12518; text-align: right;">
                            <i class="fa fa-dollar " style="margin-left: 5px;"></i> Conceder Desconto
                        </button>
                    </div> 
                </div>

              </div>

              <table class="table table-striped table-hover">
                  <thead>
                      <tr class="active">
                          <th class="text-center"></th>
                          <th class="text-center">Código</th>
                          <th>Conta</th>
                          <th>Setor</th>
                          <th>Descrição</th>
                          <th class="text-left">Emissão</th>
                          <th class="text-left">Vencimento</th>
                          <th class="text-left">Recebimento</th>
                          <th class="text-center">Situação</th>
                      </tr>
                  </thead>

                  <tbody>
                      <tr x-show="loading">
                          <td colspan="9">
                              <div class="line">
                                  <div class="loading"></div>
                                  <span style="font-size: 1.2em; font-style: italic;">Atualizando Registros...</span>
                              </div>
                          </td>
                      </tr>
 

                      <template x-if="modalRecebimento">
                          <template x-for="(conta, index) in modalRecebimento">
 
                              <tr>
                                  <td class="text-center">
                                      <code class="text-center" style="cursor: pointer;"
                                            x-show="(modalCompleto.recebido == 'N')"
                                            x-on:click="excluirLancamentoFinanceiro(conta)"><i class="fa fa-trash"
                                            style="margin-right: 0px; padding: 5px;"></i></code>
                                  </td>

                                  <th class="text-center">
                                      <span style="font-size: 1.2em; font-style: italic;"
                                          class="btn btn-default btn-rounded btn-xs "
                                          x-html="conta.cd_documento_boleto "></span>
                                  </th>

                                  <td>
                                      <span x-text="conta.conta?.nm_conta"></span>
                                      <span style="font-size: 12px; font-style: italic;"><br><b>Forma de Pagamento:
                                          </b>
                                          <span
                                              x-text="(conta.forma?.nm_forma_pag) ? conta.forma?.nm_forma_pag : ' -- '">
                                          </span>
                                      </span>
                                  </td>

                                  <td>
                                      <span x-text="(conta.setor?.nm_setor) ? conta.setor?.nm_setor : ' -- '"></span>
                                      <span style="font-size: 12px; font-style: italic;"><br><b>Marca: </b>
                                          <span x-text="(conta.marca?.nm_marca) ? conta.marca?.nm_marca : ' -- '">
                                          </span>
                                      </span>
                                  </td>

                                  <td>
                                      <span x-text="conta.ds_boleto"></span>
                                  </td>

                                  <td>
                                      <span x-text="(conta.dt_emissao) ? FormatData(conta.dt_emissao) : ' -- '"></span>
                                      <span style="font-size: 12px; font-style: italic;"><br><b>Nr.Documento: </b>
                                          <span x-text="(conta.doc_boleto) ? conta.doc_boleto : ' -- '">
                                          </span>
                                      </span>
                                  </td>

                                  <td>
                                      <span
                                          x-text="(conta.dt_vencimento) ? FormatData(conta.dt_vencimento) : ' -- '"></span>
                                      <span style="font-size: 12px; font-style: italic;"><br><b>Valor: </b>
                                          <span x-text="(conta.vl_boleto) ? formatValor(conta.vl_boleto) : ' R$ -- '">
                                          </span>
                                      </span>
                                  </td>

                                  <td>
                                      <span
                                          x-text="(conta.dt_pagrec) ? FormatData(conta.dt_pagrec) : ' -- / -- / ---- '"></span>
                                      <span style="font-size: 12px; font-style: italic;"><br><b>Valor: </b>
                                          <span x-text="(conta.vl_pagrec) ? formatValor(conta.vl_pagrec) : ' R$ -- '">
                                          </span>
                                      </span>
                                  </td>

                                  <td class="text-center" style="max-width: 80px; min-width: 70px; ">
                                      <template x-if="conta.situacao=='QUITADO'">
                                          <span class="label label-recebido"
                                              style="cursor: pointer;display: block;font-size: 11px; padding-bottom: 5px; padding-top: 3px;"
                                              x-text="conta.status">
                                          </span>
                                      </template>
                                      <template x-if="conta.situacao=='ABERTO'">
                                          <span class="label label-warning"
                                              style="cursor: pointer;display: block;font-size: 11px; padding-bottom: 5px; padding-top: 3px;"
                                              x-text="conta.status">
                                          </span>
                                      </template>
                                  </td>

                              </tr>
                          </template>
                      </template>

                      <template x-if="inputsConta.relacao_desconto">
                            <tr >
                                <td colspan="8">
                                    <div class="line">
                                        <span x-html="inputsConta.relacao_desconto"></span>
                                    </div>
                                </td>
                                <td> 
                                    <span class="label label-danger" x-on:click="excluirDesconto"
                                        x-show="(modalCompleto.recebido == 'N')"
                                        style="cursor: pointer;display: block;font-size: 11px; padding-bottom: 5px; padding-top: 3px;"
                                        x-text="'Desconto'">
                                    </span>
                                </td> 
                            </tr>
                      </template>

                  </tbody>
              </table>

              <template x-if="!modalRecebimento">
                  <p class="text-center" style="padding: 1.5em">

                      <img src="{{ asset('assets\images\sem-dinheiro.png') }}"> <br>
                      Não há recebimento para esse Atendimento
                  </p>
              </template>

          </div>

          <template x-if="(modalCompleto.situacao_conta=='A')">
              <div style="text-align: center;">
                  <img src="{{ asset('assets\images\conta_aberta.png') }}" width="250">
                  <h3>A Conta do Atendimento encontra-se Aberta!</h3>
              </div>
          </template>


      </div>
  </form>
