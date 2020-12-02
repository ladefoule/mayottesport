const BOUTONSUPPRESSION = '<i class="fas fa-trash-alt"></i>';
const BOUTONAJOUTER = '<i class="fas fa-plus"></i>';
const BOUTONVUE = '<i class="fas fa-eye"></i>';
const BOUTONEDIT = '<i class="fas fa-edit"></i>';
const BOUTONLISTE = '<i class="fas fa-list-ul"></i>';
const INPUTSAVERIFIER = `textarea:not([disabled]),
                        select:not([disabled]),
                        input:not([disabled]):not([type=hidden]):not([type=submit]):not([type=button]):not([type=reset]):not([type=checkbox])`

/* FONCTIONS DE RACCOURCIS */
    /**
     * Raccourci pour elem.querySelector(query)
     *
     * @param {string} query - la requète
     * @param {object} elem - objet dans lequel on effectue la recherche (par défaut => document)
     */
    var qs = (query, elem = document) => elem.querySelector(query)

    /**
     * Raccourci pour elem.querySelectorAll(query)
     *
     * @param {string} query - la requète
     * @param {object} elem - objet dans lequel on effectue la recherche (par défaut => document)
     */
    var qsa = (query, elem = document) => elem.querySelectorAll(query)

    /**
     * Raccourci pour console.log(elem)
     *
     * @param {object} elem - object à afficher
     */
    var cl = elem => {console.log(elem)}

    /**
     * Raccourci pour document.createElement(query)
     *
     * @param {object} elem - objet à créer
     */
    var dce = elem => document.createElement(elem)
/* FIN FONCTIONS DE RACCOURCIS */

/**
 * Renvoie un nombre aléatoire entre min et max. La fonction donne souvent des résultats proches des extrémités.
 *
 * @param {int} min - minimum
 * @param {int} max - maximum
 */
function randInt(min, max) {
    return Math.floor(min + Math.random() * (max - min));
}

/**
 *  Centrer un élément inclu dans un contenant (taille fixe) scrollable sur l'axe des X
 *
 * @param {object} target - Element à center
 * @param {object} outer - Element scrollable de gauche à droite (X) qui contient target
 */
function centerItFixedWidth(target, outer){
    var out = $(outer);
    var tar = $(target);
    var x = out.width();
    var y = tar.outerWidth(true);
    var z = tar.index();
    out.scrollLeft(Math.max(0, (y * z) - (x - y)/2));
}

/**
 *  Centrer un élément inclu dans un contenant (taille variable) scrollable sur l'axe des X
 *
 * @param {object} target - Element à center
 * @param {object} outer - Element scrollable de gauche à droite (X) qui contient target
 */
function centerItVariableWidth(target, outer){
    var out = $(outer);
    var tar = $(target);
    var x = out.width();
    var y = tar.outerWidth(true);
    var z = tar.index();

    var q = 0;
    var m = out.find('a');
    //Just need to add up the width of all the elements before our target.
    for(var i = 0; i < z; i++){
        q+= $(m[i]).outerWidth(true);
    }
    out.scrollLeft(Math.max(0, q - (x - y)/2));
}

/**
 * Cette méthode ajoute des EventListeners au formulaire,
 * ainsi qu'à tous les inputs/textarea/select/... inclus dans le formulaire.
 *
 * @param {string} idFormulaire - id du formulaire
 */
function verifierMonFormulaireEnJS(idFormulaire){
    var form = qs('#'+idFormulaire)
    form.addEventListener('submit', function(e){
        e.preventDefault()
        submitForm(idFormulaire)
    })

    qsa(INPUTSAVERIFIER, form).forEach(function(elem){
        elem.addEventListener('change', e => checkOne(e.target))
    })

    // Evénements pour tous les inputs
    // $('#'+idFormulaire).on('change', ':input', function(){checkOne($(this)[0])})
}

/**
 * Vérifie tout le formulaire. S'arrète et renvoie false dès qu'il rencontre un champ non valide.
 *
 * @param {string} idFormulaire
 */
function checkAll(idFormulaire){
    var form = qs('#'+idFormulaire)
    let inputs = qsa(INPUTSAVERIFIER, form)

    for (let i = 0; i < inputs.length; i++)
        if(checkOne(inputs[i]) == false)
            return false

    return true
}

/**
 * Vérifie si le contenu de l'élement est valide ou non.
 *
 * @param {object} elem - élément à vérifier
 */
function checkOne(elem) {
    let messageErreur = elem.dataset['msg']
    let divMessageErreur = qs('#messageErreur')
    divMessageErreur.innerHTML = messageErreur

    if((elem.value == '' && !elem.classList.contains('input-optionnel')) // Si le champ est vide mais qu'il n'a pas la classe input-optionnel
         || elem.checkValidity() == false){ // ou si la validation est fausse (type incorrect par exemple)
        divMessageErreur.style.display = 'block'
        divMessageErreur.classList.remove('d-none')
        elem.classList.add('is-invalid')
        elem.classList.remove('is-valid')
        elem.focus()
        // cl(elem.nodeName)
        return false
    }

    divMessageErreur.style.display = 'none'
    divMessageErreur.classList.add('d-none')
    elem.classList.remove('is-invalid')
    elem.classList.add('is-valid')
    return true
}

/**
 * Soumission d'un formulaire
 *
 * @param {string} idFormulaire - id du formulaire
 */
function submitForm(idFormulaire) {
    var form = qs('#'+idFormulaire)
    if(checkAll(idFormulaire))
        form.submit()
}

/**
 * Permet de récupérer tous les inputs d'un formulaire exceptés les champs sans name, disabled, file input et checkbox/radio non cochés
 *
 * @param {formObject} formEle - le formulaire
 */
const serialize = function(formEle) {
    // Get all fields
    const fields = [].slice.call(formEle.elements, 0);

    return fields
        .map(function(ele) {
            const name = ele.name;
            const type = ele.type;

            // We ignore
            // - field that doesn't have a name
            // - disabled field
            // - `file` input
            // - unselected checkbox/radio
            if (!name ||
                ele.disabled ||
                type === 'file' ||
                (/(checkbox|radio)/.test(type) && !ele.checked))
            {
                return '';
            }

            // Multiple select
            if (type === 'select-multiple') {
                return ele.options
                    .map(function(opt) {
                        return opt.selected
                            ? `${encodeURIComponent(name)}=${encodeURIComponent(opt.value)}`
                            : '';
                    })
                    .filter(function(item) {
                        return item;
                    })
                    .join('&');
            }

            return `${encodeURIComponent(name)}=${encodeURIComponent(ele.value)}`;
        })
        .filter(function(item) {
            return item;
        })
        .join('&');
};

/**
 * Récupère la liste de tous les éléments en AJAX et met à jour le contenu du tableau
 *
 * @param {string} url - url de la liste (requète envoyée en Ajax)
 * @param {string} idTable - id de la table
 */
function listeAjax(url, idTable)
{
    $.ajax({
        type: 'GET',
        url: url,
        success:function(data){
            let table = qs('#'+idTable)
            let tbody = qs('tbody', table)
            let newTbody = dce('tbody')
            newTbody.innerHTML = data
            table.replaceChild(newTbody, tbody)
            triDataTables(idTable)
        }
    })
}

/**
 * Suppression de plusieurs éléments (dont l'id est inclu dans le tableau ids) en AJAX.
 *
 * @param {array} params - tableau des paramètres. Doit contenir les clés : urlSupprimer, urlLister, idTable, token, ids
 */
function suppressionAjax(params)
{
    let urlSupprimer = params['urlSupprimer']
    let urlLister = params['urlLister']
    let ids = params['ids']
    let token = params['token']
    let idTable = params['idTable']
    $.ajax({
        type: 'POST',
        url: urlSupprimer,
        data: {ids:ids, _token:token},
        success: function(){
            listeAjax(urlLister, idTable)
        }
    });
}

/**
 * Demande de confirmation sur les boutons supprimer + Envoi de la requète en AJAX si confirmé
 *
 * @param {array} params - tableau de paramètres avec les index suivants : urlSupprimer, urlLister, idTable, token
 */
function supprimerUnElement(params){
    let idTable = params['idTable']

    $('#'+idTable).on("click", 'a[title="Supprimer"]', function(e){
        e.preventDefault()
        let element = qs('td:nth-child(2)', this.closest('tr')).innerHTML
        let tdAvecInput = qs('td:nth-child(1)', this.closest('tr'))
        let id = qs('input[type=checkbox]', tdAvecInput).value
        params['ids'] = [id]
        let ok = confirm("Supprimer définitivement : " + element + "?")
        if(ok){
            suppressionAjax(params)
        }
    });
}

/**
 * Ajout d'un EventListener (click) sur le bouton qui a l'id #multi-suppressions.
 * Suppression de tous les éléments cochés en cas d'action sur ce bouton.
 *
 * @param {string} params - tableau de paramètres avec les index suivants : urlSupprimer, urlLister, idTable, token
 */
function supprimerSelection(params)
{
    let idTable = params['idTable']
    $('#multi-suppressions').on("click", function(){
        let ok = confirm("Supprimer définitivement les éléments sélectionnés ?")
        if(ok){
            let ids = []
            var lesCheckbox = qsa('input[type=checkbox]', qs('#'+idTable+'>tbody'))
            for (let i = 0; i < lesCheckbox.length; i++)
                if(lesCheckbox[i].checked){
                    let id = lesCheckbox[i].value
                    ids.push(id)
                }

            params['ids'] = ids
            suppressionAjax(params)
        }

    })
}

/**
 * On met un lien à tous les tr qui ont la classe tr_lien
 *
 * @param {string} idTable - id de la table
 */
function trAvecHref(idTable){
    $('#'+idTable).on("click", 'tr.tr_lien', function(){
        let href = this.dataset['href'] ?? ''
        if(href != '')
            window.location.href = href
    });
}

/**
 * On applique la librairie DataTables au tableau en le triant par la 2ème colonne par défaut
 *
 * @param {integer} idTable
 * @param {integer} numeroColonne - Le numéro de la colonne
 * @param {string} sens - Le sens de tri asc ou desc
 */
function triDataTables(idTable, numeroColonne = 1, sens = 'asc') {
    if((sens != 'asc' && sens != 'desc') || numeroColonne < 0)
        return false;

    $('#'+idTable).DataTable( {
        destroy: true, // On "vide le cache" de l'objet DataTables
        paging: true, // Activation de la pagination
        language: {
            url : "../json/datatables.json" // Traduction en français
        },
        order : [[ numeroColonne, sens ]], // Colonne et sens de tri
        "columnDefs": [ {
            "targets": [0,-1], // 0 => 1ère colonne à gauche, -1 => 1ère colonne à droite
            "searchable": false, // Pas de recherche possible
            "orderable": false // Pas triables
          } ]
    } );
}

/**
 * Requète AJAX dont les données seront injectées dans un select.
 * Les index inclus dans params : idSelect, texteOptionDefaut, url, method, data
 *
 * @param {array} params - tableau des paramètres
 */
function ajaxSelect(params)
{
    var select = qs('#'+params['idSelect'])

    fetch(params['url'], {
        method:params['method'],
        body:JSON.stringify(params['data']),
        headers: {"Content-type": "application/json; charset=UTF-8"}
    })
    .then(res => res.json())
    .then(res => {
        if(res.length > 0){
            select.innerHTML = ''
            let optionDefaut = dce('option')
            optionDefaut.innerHTML = 'Sélectionner'
            optionDefaut.value = ''
            select.appendChild(optionDefaut)

            for(let i in res){
                let option = dce('option')
                option.value = res[i]['id']
                option.innerHTML = res[i]['nom']
                select.appendChild(option)
            }
        }
    })
    .catch(err => cl(err))
}

/**
 * La fonction permet de cocher/décocher tous les checkbox inclus dans
 * l'élément qui a l'id #idBloc en fonction du click sur l'objet qui a l'id #tout
 *
 * @param {string} idBloc - id du bloc qui contient les checkbox
 */
function toutCocherDecocher(idBloc) {
    var tout = qs('#tout')
    tout.addEventListener('click', function(e){
        var lesCheckbox = qsa('input[type=checkbox]', qs('#'+idBloc))
        let action = this.dataset['action']
        if(action == 'cocher'){
            for (let i = 0; i < lesCheckbox.length; i++)
                if(lesCheckbox[i].disabled == false)
                    lesCheckbox[i].checked = true

            tout.dataset['action'] = 'decocher'
        }else if(action == 'decocher'){
            for (let i = 0; i < lesCheckbox.length; i++)
                if(lesCheckbox[i].disabled == false)
                    lesCheckbox[i].checked = false

            tout.dataset['action'] = 'cocher'
        }
    })
}

/**
 * Retour sur la page précédente
 */
function retour()
{
    qs('a.back').addEventListener('click', (e) => {
        e.preventDefault()
        window.history.back()
    })
}
