function headerBuilder(){
    new Sortable(document.getElementById("disponiveis"), {
    animation: 150,
    ghostClass: 'blue-background-class',
    group: {
        name: 'shared',
        pull: 'clone' // To clone: set pull to 'clone'
    },
    
    
});


    new Sortable(document.getElementsByClassName("esquerdaLayout")[0], {
    group: 'shared', // set both lists to same group
    animation: 150
});


 new Sortable(document.getElementsByClassName("direitaLayout")[0], {
    group: 'shared', // set both lists to same group
    animation: 150
});


 new Sortable(document.getElementsByClassName("centroLayout")[0], {
    group: 'shared', // set both lists to same group
    animation: 150
});



}
