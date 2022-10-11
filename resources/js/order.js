function samovivoz() {
    $(".tochka-samovivoza").css({
        "display" : "flex",
    });

    $(".address").css({
        "display" : "flex",
    })

    $(".method-oplati").css({
        "display" : "flex",
    })
}

function samovivozClose() {
    $(".tochka-samovivoza").css({
        "display" : "none",
    });

    $(".address").css({
        "display" : "none",
    })
}

function dostavka() {
    $(".region").css({
        "display" : "flex",
    });

    $(".naselyonniy-punkt").css({
        "display" : "flex",
    })

    $(".address-input").css({
        "display" : "flex",
    })

    $(".method-oplati").css({
        "display" : "flex",
    })
}

function dostavkaClose() {
    $(".region").css({
        "display" : "none",
    });

    $(".naselyonniy-punkt").css({
        "display" : "none",
    })

    $(".address-input").css({
        "display" : "none",
    })
}

$('#select1').on('change', function (e) {
    let valueSelected = this.value;


    if(valueSelected == "samovivoz") {
        samovivoz();
        dostavkaClose();
    }

    if(valueSelected == "dostavka-do-dveri") {
        samovivozClose();
        dostavka();
    }
});

$('#selectmethod').on('change', function (e) {
    $(".sled-shag").css({
        "display" : "flex",
    });
});

