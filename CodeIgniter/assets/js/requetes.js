class Popup {
    constructor() {
        this.height = 70;
        this.width = 500;
    }

    /**
     * 
     * @param {String} type 
     * @returns {Popup}
     */
    static createPopup(type) {
        switch (type) {
            case "alert":
                return new AlertPopup();
            case "information":
                return new InformationPopup();
            default:
                return null;
        }
    }
}


class AlertPopup extends Popup {
    constructor() {
        super();
        this.image = "danger.png"
        this.color = "ff0000";
        this.duration = 5000;
    }
}


class InformationPopup extends Popup {
    constructor() {
        super()
        this.image = "info.png"
        this.color = "7DE14C"
        this.duration = 3000;
    }
}


/**
 * 
 * @param {String} type 
 * @param {String} message
 */
function createPopup(type, message) {
    const popupDiv = document.getElementById("popup");
    if (popupDiv != null) {
        popupDiv.remove();
    }

    const popup = Popup.createPopup(type);
    const popupHtml = '<div class="popup" id="popup"><img class="popupLogo" src="http://172.26.82.55/assets/image/popup/' + popup.image + '"><p class="popupMessage">' + message + '</p></div>'

    document.body.innerHTML += popupHtml
    document.getElementById("popup").style.backgroundColor = "#" + popup.color;

    setTimeout(deletePopup, popup.duration); // enleve la popup après une certaine durée
}

function deletePopup() {
    const popup = document.getElementById("popup");

    if (popup != null) {
        popup.style.opacity = 0;
        setTimeout(() => {
            popup.outerHTML = ""
        }, 2000);
    }
}



const siteUrl = "http://172.26.82.55/index.php/"



function showPopup() {
    if (typeof popupType !== "undefined" && typeof popupMessage !== "undefined") {
        createPopup(popupType, popupMessage);

        // on supprime les parametres dans l'url pour eviter de les réafficher
        const url = new URL(window.location.href);
        url.searchParams.delete("popupType");
        url.searchParams.delete("popupMessage");
        window.history.pushState({}, "", url);
    }    
}


showPopup()


function request(url, params=[]) {
    const Http = new XMLHttpRequest();

    stringParam = ""
    for (param of params) {
        stringParam += "/" + param
    }

    url = url + stringParam;
    Http.open("GET", url);
    Http.send();
}

function reload(time = 0) {
    setTimeout("location.reload(true);", time);
}

// accueil/Boutique
function ajoutPanierSession(articleId, quantite=1) {
    request(siteUrl + "Panier/addSession/", [articleId, quantite]); //ajoute l'article en un exemplaire dans la session
    reload();
}

function ajoutePanierBD(userID, articleId, quantite=1) {
    request(siteUrl + "Panier/add/", [userID, articleId, quantite]);//ajoute l'article en un exemplaire dans la bd
    reload();
}

// Panier
function modifierQuantitePanierSession(articleID, quantite) {
    request(siteUrl + "Panier/updateSession", [articleID, quantite]);
    reload();
}

function modifierQuantitePanierBD(userID, articleID, quantite) {
    request(siteUrl + "Panier/update/", [userID, articleID, quantite]);
    reload();
}

function supprimerArticlePanierSession(articleID) {
    request(siteUrl + "Panier/deleteSession/", [articleID]);
    reload();
}

function supprimerArticlePanierBD(userID, articleID) {
    request(siteUrl + "Panier/delete/", [userID, articleID]);
    reload();
}

function supprimerPanierDuUser(userID) {
    request(siteUrl + "Panier/deleteAll", [userID]);
    reload();
}

function supprimerPanierDeSession() {
    request(siteUrl + "Panier/deleteAllSession");
    reload();
}

// admin
function supprimerArticleAdmin(articleID) {
    request(siteUrl + "Admin/delete", [articleID]);
    reload(200);
}

function supprimerUtilisateurAdmin(userID) {
    request(siteUrl + "Admin/deleteUser", [userID]);
    reload(200);
}
