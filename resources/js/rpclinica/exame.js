 
Alpine.data('appFormulario', () => ({
  
   
   

    async copyText(textToCopy) {
       
         
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(textToCopy);
        } else { 
            const textArea = document.createElement("textarea");
            textArea.value = textToCopy; 
            textArea.style.position = "absolute";
            textArea.style.left = "-999999px"; 
            document.body.prepend(textArea);
            textArea.select(); 
            try {
                document.execCommand('copy');
                toastr['success']('Copiado!');
            } catch (error) {
                toastr['error'](`Não foi possivel copiar. Erro: ${ error }`);
            } finally {
                textArea.remove();
            }
        }
    
},
 
}));

 

 

 
