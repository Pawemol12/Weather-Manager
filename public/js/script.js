const ALERT_INFO = 0,
    ALERT_SUCCESS = 1,
    ALERT_WARNING = 2,
    ALERT_ERROR = 3;

function alertBS(selector, text, type, replace = true) {
    var classes = [
        'alert-info',
        'alert-success',
        'alert-warning',
        'alert-danger'
    ];

    var titles = [
        'Informacja.',
        'Sukces!',
        'Ostrzeżenie!',
        'Błąd!',
    ];

    if (type < 0 || type > classes.length) {
        return;
    }

    var alertBody = '<div class="alert alert-dismissable ' + classes[type] + '"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>' + titles[type] + '</strong> ' + text + '</div>';

    if (replace) {
        $(selector).html(alertBody);
    } else {
        $(selector).append(alertBody);
    }
}

function copyAlerts(srcSelector, destSelector, override = false) {
    var sourceHTML = $(srcSelector).html();
    $(srcSelector).html('');

    if (override) {
        $(destSelector).html(sourceHTML);
    } else {
        $(destSelector).append(sourceHTML);
    }
}

function removeFormErrors(formSelector) {
    var formGroup = $(formSelector).find('.has-error');
    formGroup.removeClass('has-error');
    $(formSelector).find('.help-block, .alert').remove();
}

function bindSearchForm(searchFormSelector, tableWrapperSelector = '#TableWrapper') {
    $('body').on('submit', searchFormSelector, function (e) {
        e.preventDefault();
        $(this).ajaxSubmit(
            {
                success: function (resp) {
                    if (typeof resp === 'object') {
                        switch (resp.type) {
                            case 'alert': {
                                if (resp.alert_type != ALERT_INFO && resp.alert_type != ALERT_SUCCESS) {
                                    removeFormErrors(searchFormSelector);
                                }

                                alertBS('#alertContainer', resp.message, resp.alert_type);
                                break;
                            }
                        }
                    } else {
                        $html = $(resp);
                        if ($html.find('table')) {
                            removeFormErrors(searchFormSelector);
                            $(tableWrapperSelector).html(resp);
                        }
                        //if ($html[0].tagName == 'FORM') {
                        //     $(this).html($html.html());
                        //} else {

                        //}
                    }
                },
                error: function () {
                    alertBS('#alertContainer', 'Wystąpił błąd podczas wyszukiwania', ALERT_ERROR);
                },
            });
    });
}

function bindAjaxKnpPaginator(tableWrapper) {
    //Paginacja Ajaxowa i sortowanie
    $('body').on('click', tableWrapper + ' .navigation a, ' + tableWrapper + ' .custom-sort a', function (e) {

        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: $(this).attr('href'),
            success: function (resp) {
                if (typeof resp === 'object') {
                    switch (resp.type) {
                        case 'alert': {
                            alertBS('#alertContainer', resp.message, resp.alert_type);
                            break;
                        }
                    }
                } else {
                    $(tableWrapper).html(resp);
                    $('[data-toggle="tooltip"]').tooltip();
                }
            },
            error: function () {
                alertBS('#alertContainer', 'Wystąpił błąd podczas wyszukiwania', 3);
            },
        });
    });

}

function bindAjaxModalForm(btnSelector, modalFormSelector = "#FormModal", successHandler = null) {
    $('body').on('click', btnSelector, function (e) {
        e.preventDefault();
        var actionUrl = $(this).data('action');
        if (!actionUrl) {
            return;
        }

        $.ajax({
            url: actionUrl,
            success: function (data) {
                if (typeof data === 'object') { //W przypadku zwróconego jsona
                    if (data.type == 'alert') {
                        alertBS('#alertContainer', data.message, data.alert_type);
                    }
                } else {
                    $('#ModalContainer').html(data);
                    $('#ModalContainer ' + modalFormSelector).on('shown.bs.modal', function (e) {
                        if (successHandler) {
                            successHandler();
                        }
                        $('.selectpicker').selectpicker();
                        hideSpoilers();
                    });
                    $('#ModalContainer ' + modalFormSelector).modal();
                }
            },
            error: function () {
                alertBS('#alertContainer', 'Błąd podczas łączenia z serwerem.', 3);
            }
        });
    });
}

function bindForm(formSelector, tableWrapperSelector = '#TableWrapper', modalSelector='', actionHandler=null) {
    $('body').on('submit', formSelector, function (e) {
        e.preventDefault();
        $(this).ajaxSubmit(
            {
                success: function (resp) {
                    if (typeof resp === 'object') {
                        switch (resp.type) {
                            case 'alert': {
                                alertBS('#alertContainer', resp.message, resp.alert_type);
                                if (modalSelector) {
                                    $(modalSelector).modal('hide');
                                    $(modalSelector).modal('dispose');
                                    $(modalSelector).remove();
                                }
                                break;
                            }
                        }
                    } else {
                        $html = $(resp);
                        if ($html[0].tagName == 'FORM') {
                            $(formSelector).html(resp);
                        }
                        else {
                            $(tableWrapperSelector).html(resp);
                            copyAlerts('#hiddenAlerts', '#alertContainer', true);
                            if (modalSelector) {
                                $(modalSelector).modal('hide');
                                $(modalSelector).modal('dispose');
                                $(modalSelector).remove();
                            }
                        }
                    }

                    if (actionHandler) {
                        actionHandler();
                    }
                },
                error: function () {
                    alertBS('#alertContainer', 'Wystąpił błąd podczas wyszukiwania', ALERT_ERROR);
                },
            });
    });
}

function bindDeleteModal(modalSelector = '#FormModal', confirmBtnSelector = '#confirmBtn', cancelButtonSelector='#cancelBtn', tableWrapperSelector = '#TableWrapper')
{
    //Kliknięcie tak
    $('body').on('click', modalSelector + ' ' + confirmBtnSelector, function (e) {
        e.preventDefault();
        var actionUrl = $(this).data('action');
        if (!actionUrl) {
            return;
        }

        $.ajax({
            url: actionUrl,
            success: function (data) {
                if (typeof data === 'object') { //W przypadku zwróconego jsona
                    if (data.type == 'alert') {
                        alertBS('#alertContainer', data.message, data.alert_type);
                    }
                } else {
                    $(tableWrapperSelector).html(data);
                    copyAlerts('#hiddenAlerts', '#alertContainer', true);
                }
            },
            error: function () {
                alertBS('#alertContainer', 'Błąd podczas łączenia z serwerem.', 3);
            }
        });
    });

    //Kliknięcie nie
    $('body').on('click', modalSelector + ' ' + cancelButtonSelector, function (e) {
        $(modalSelector).modal('hide');
        $(modalSelector).modal('dispose');
        $(modalSelector).remove();
    });
}

function hideSpoilers() {
    var $classy = '.card.autocollapse';

    var $found = $($classy);
    $found.find('.card-body').hide();
    $found.removeClass($classy);
}

$(document).on('click', '.card div.clickable', function (e) {
    var $this = $(this); //Heading
    var $panel = $this.parent('.card');
    var $panel_body = $panel.children('.card-body');
    var $display = $panel_body.css('display');

    if ($display == 'block') {
        $panel_body.slideUp();
    } else if($display == 'none') {
        $panel_body.slideDown();
    }
});

function fixDateTimePicker() {
    $.fn.datetimepicker.Constructor.Default = $.extend({},
        $.fn.datetimepicker.Constructor.Default,
        { icons:
                { time: 'fas fa-clock',
                    date: 'fas fa-calendar',
                    up: 'fas fa-arrow-up',
                    down: 'fas fa-arrow-down',
                    previous: 'fas fa-arrow-circle-left',
                    next: 'fas fa-arrow-circle-right',
                    today: 'far fa-calendar-check-o',
                    clear: 'fas fa-trash',
                    close: 'far fa-times' } });
}

$(document).ready(function () {
    //Multilevel dropdown
    $('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
        if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
        }
        var $subMenu = $(this).next(".dropdown-menu");
        $subMenu.toggleClass('show');

        $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
            $('.dropdown-submenu .show').removeClass("show");
        });

        return false;
    });

    hideSpoilers();
    fixDateTimePicker();

    $('.datetimepicker').datetimepicker({
        locale: 'pl'
    });
});