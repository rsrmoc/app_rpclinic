
  @include('rpclinica.consultorio.formularios.consultorio.impressao.rpclinic.includes.header_footer_padrao',[$relatorio,$execultante])

  <main >
    <br>  
    <span style="font-style: italic; font-weight: 700"> Anamnese: </span><br>
    {!! ($agendamento['anamnese']) ? $agendamento['anamnese'] : ' --- ' !!}    
    <br><br> 
    <span style="font-style: italic; font-weight: 700"> Exame Físico: </span><br>
    {!! ($agendamento['exame_fisico']) ? $agendamento['exame_fisico'] : ' --- ' !!}  
    <br><br> 
    <span style="font-style: italic; font-weight: 700"> Hipótese Diagnóstica: </span><br>
    {!! ($agendamento['hipotese_diagnostica']) ? $agendamento['hipotese_diagnostica']  : ' --- ' !!} 
    <br><br> 
    <span style="font-style: italic; font-weight: 700"> Conduta: </span><br>
    {!! ($agendamento['conduta']) ? $agendamento['conduta'] : ' --- ' !!} 
  </main>
</body>
</html>