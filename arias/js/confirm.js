    function confirmdelete(url) {
        var agree=confirm(" Are you sure you wish to delete this?");
        if (agree)
        location.replace(url);
    }

    function confirmunpost(url) {
        var agree=confirm(" Are you sure you wish to unpost this?");
        if (agree)
        location.replace(url);
    }

    function confirmemail(url) {
        var agree=confirm(" OK to send an email?");
        if (agree)
        location.replace(url);
    }
