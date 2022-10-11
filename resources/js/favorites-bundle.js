const FavoritesBundleClass = (function () {
    'use strict';

    let self = this;

    self.addFavoriteUrl = window.customConfig.addFavoriteUrl;
    self.fetchFavoritesUrl = window.customConfig.fetchFavoritesUrl;
    self.removeFavoriteUrl = window.customConfig.removeFavoriteUrl;
    self.userAuthenticated = window.customConfig.userAuthenticated;
    self.favoriteItems = [];
    self.favoriteActionElementSelector = '.favorite-action-trigger';
    self.favoriteActionElements = $(self.favoriteActionElementSelector);
    self.favoritesCountElement = $('.favorite__sup');
    self.removeCallback = null;

    self.refreshElements = function () {
        self.favoriteActionElements = $(self.favoriteActionElementSelector);
        self.initTriggersActives();
    }

    self.initTriggersActives = function () {
        self.favoriteActionElements.removeClass('active');

        self.favoriteItems.forEach(itemId => {
            self.favoriteActionElements.filter(`[data-item-id="${itemId}"]`).addClass('active');
        });

        self.favoritesCountElement.text(self.favoriteItems.length);
        self.favoritesCountElement.show();
    }

    if (!window.customConfig.userAuthenticated || window.customConfig.userIsAdmin) {
        return false;
    }

    self.fetchFavorites = async function () {
        let response = await fetch(self.fetchFavoritesUrl);

        self.favoriteItems = await response.json();
    }

    self.addFavorite = async function (itemId) {
        self.favoriteItems.push(itemId);

        const response = await fetch(self.addFavoriteUrl, {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            redirect: 'follow',
            referrerPolicy: 'no-referrer',
            body: JSON.stringify({
                itemId: itemId
            })
        });

        return await response.text();
    }

    self.removeFavorite = async function (itemId) {
        self.favoriteItems.splice(self.favoriteItems.indexOf(itemId), 1);

        const response = await fetch(self.removeFavoriteUrl, {
            method: 'DELETE',
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            redirect: 'follow',
            referrerPolicy: 'no-referrer',
            body: JSON.stringify({
                itemId: itemId
            })
        });

        return await response.text();
    }

    self.getFavorites = function () {
        return self.favoriteItems;
    };

    self.hasItem = function (itemId) {
        return self.favoriteItems.indexOf(itemId) !== -1;
    };

    self.fetchFavorites().then(() => {
        self.initTriggersActives();
    });

    $(document).on('click', self.favoriteActionElementSelector, function () {
        let element = $(this);
        const itemId = parseInt(element.attr('data-item-id'));

        if (itemId && self.favoriteItems.indexOf(itemId) === -1) {
            self.addFavorite(itemId).then(() => {
                self.initTriggersActives();
            });
        } else {
            self.removeFavorite(itemId).then(() => {
                self.initTriggersActives();

                if (typeof self.removeCallback === "function") {
                    self.removeCallback(itemId);
                }
            });
        }
    });

    return self;
});

$('.favorite-action-trigger').click(function (){
    $('.favorite-action-trigger .img-fluid').toggle().toggleClass('img-fluid-change_color')
    $('.add__favorite').toggle().toggleClass('add__favorite_border')
})

window.favoritesBundle = new FavoritesBundleClass();
