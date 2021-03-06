function journeesMultiples(params) {
    var selectSports = params['selectSports']
    var selectCompetitions = params['selectCompetitions']
    var selectSaisons = params['selectSaisons']
    var inputToken = params['inputToken']
    var method = params['method']

    // S'il y a un changement dans le SELECT sports, alors on recherche les competitions associés
    // et en même temps on vide le SELECT des saisons et la DIV qui contient les journées
    selectSports.onchange = function () {
        // $('#sport_id').on('change', function(){
        selectSaisons.innerHTML = ''
        if (selectSports.value == '') {
            selectCompetitions.innerHTML = ''
            return false;
        }
        let donneesRequeteAjax = {
            url: params['urlAjaxCompetitions'],
            method: method,
            idSelect: 'competition_id',
            data: {
                sport_id: selectSports.value,
                _token: inputToken.value
            }
        }
        ajaxSelect(donneesRequeteAjax)
    }

    // S'il y a un changement dans le SELECT competitions, alors on recherche les saisons associées
    // et en même temps on vide la DIV qui contient les journées
    selectCompetitions.onchange = function () {
        // $('#competition_id').on('change', function(){
        if (selectCompetitions.value == '') {
            selectSaisons.innerHTML = ''
            return false;
        }
        let donneesRequeteAjax = {
            url: params['urlAjaxSaisons'],
            method: method,
            idSelect: 'saison_id',
            data: {
                competition_id: selectCompetitions.value,
                _token: inputToken.value
            }
        }
        ajaxSelect(donneesRequeteAjax)
    }

    // S'il y a un changement dans le SELECT saisons, alors on recherche les journées associées
    // S'il n'y a pas encore de journées crées, alors on génère NB journées (attribut nb_journees de la table saisons)
    selectSaisons.onchange = function () {
        // $('#saison_id').on('change', function(){
        let idSaison = selectSaisons.value
        if (idSaison == '') {
            return false;
        }

        $.ajax({
            url: params['urlAjaxUrlEditMultiJournees'],
            method: method,
            data: {saison_id: idSaison, _token: inputToken.value},
            success: function (data) {
                if (data != 'Erreur') {
                    window.location.href = data
                }
            }
        })
    }
}
