/**
 * @author Paweł Lodzik <Pawemol12@gmail.com>
 */

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

function bindWeatherSearchForm() {
    $('body').on('submit', '#WeatherSearchForm', function (e) {
        e.preventDefault();
        $(this).ajaxSubmit(
            {
                success: function (resp) {
                    if (typeof resp === 'object') {
                        switch (resp.type) {
                            case 'alert': {
                                alertBS('#alertContainer', resp.message, resp.alert_type);
                                break;
                            }
                        }
                    } else {
                        $('#WeatherShowPanelWrapper').html(resp);
                        copyAlerts('#hiddenAlerts', '#alertContainer', true);
                    }
                },
                error: function () {
                    alertBS('#alertContainer', 'Wystąpił błąd podczas wyszukiwania', ALERT_ERROR);
                },
            });
    });
}

$(document).ready(function () {
    //Pokazywanie pogody dla miasta
    bindWeatherSearchForm();

    //Akcje na miastach
    bindAjaxModalForm('#addCityBtn', '#CityFormModal');
    bindAjaxModalForm('.btnCityEdit', '#CityFormModal');
    bindAjaxModalForm('.btnCityDelete', '#CityDeleteModal');

    bindForm('#CityForm','#CitiesTableWrapper', '#CityFormModal');
    bindDeleteModal('#CityDeleteModal', '#confirmBtn', '#cancelBtn', '#CitiesTableWrapper');

});