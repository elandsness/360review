function toggle (){
    var current = feedback.submit.value;
    switch (current){
        case 'Save':
            feedback.submit.value = 'Complete';
        break;
        case 'Complete':
            feedback.submit.value = 'Save';
        break;
    }
}

