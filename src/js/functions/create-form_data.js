function create_form_data(form){
    var formData = new FormData(),
        formInputs = form.serializeObject(),
        fileInputs = form.find('input[type=file]');

    for (var key in formInputs){
        formData.append(key, formInputs[key]);
    }
    
    for (let i = 0; i < fileInputs.length; i++) {
        formData.append(fileInputs[i].name+'_qty', fileInputs[i].files.length);
        for (let count = 0; count < fileInputs[i].files.length; count++) {
            let file = fileInputs[i].files[count];
            formData.append(fileInputs[i].name+'_'+count, file);
        }
    }

    return formData;
}