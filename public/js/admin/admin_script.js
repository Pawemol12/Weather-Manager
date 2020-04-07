$(document).ready(function () {
    //Użytkownicy
    bindSearchForm('#UserSearchForm', '#adminUsersTableWrapper');
    bindAjaxKnpPaginator('#adminUsersTableWrapper');
    bindAjaxModalForm('#addUserBtn', '#UserFormModal');
    bindAjaxModalForm('.btnUserEdit', '#UserFormModal');
    bindAjaxModalForm('.btnUserDelete', '#UserDeleteModal');
    bindForm('#UserForm','#adminUsersTableWrapper', '#UserFormModal');
    bindDeleteModal('#UserDeleteModal', '#confirmBtn', '#cancelBtn', '#adminUsersTableWrapper');

    //Użytkownicy Google
    bindSearchForm('#GoogleUserSearchForm', '#adminGoogleUsersTableWrapper');
    bindAjaxKnpPaginator('#adminGoogleUsersTableWrapper');
    bindAjaxModalForm('.btnGoogleUserEdit', '#GoogleUserFormModal');
    bindAjaxModalForm('.btnGoogleUserDelete', '#GoogleUserDeleteModal');
    bindForm('#GoogleUserForm','#adminGoogleUsersTableWrapper', '#GoogleUserFormModal');
    bindDeleteModal('#GoogleUserDeleteModal', '#confirmBtn', '#cancelBtn', '#adminGoogleUsersTableWrapper');

    //Role
    bindSearchForm('#RoleSearchForm', '#adminRolesTableWrapper');
    bindAjaxKnpPaginator('#adminRolesTableWrapper');
    bindAjaxModalForm('#addRoleBtn', '#RoleFormModal');
    bindAjaxModalForm('.btnRoleEdit', '#RoleFormModal');
    bindAjaxModalForm('.btnRoleDelete', '#RoleDeleteModal');
    bindForm('#RoleForm','#adminRolesTableWrapper', '#RoleFormModal');
    bindDeleteModal('#RoleDeleteModal', '#confirmBtn', '#cancelBtn', '#adminRolesTableWrapper');

    //Akcje
    bindSearchForm('#ActionSearchForm', '#adminActionsTableWrapper');
    bindAjaxKnpPaginator('#adminActionsTableWrapper');
    bindAjaxModalForm('#addActionBtn', '#ActionFormModal');
    bindAjaxModalForm('.btnActionEdit', '#ActionFormModal');
    bindAjaxModalForm('.btnActionDelete', '#ActionDeleteModal');
    bindForm('#ActionForm','#adminActionsTableWrapper', '#ActionFormModal');
    bindDeleteModal('#ActionDeleteModal', '#confirmBtn', '#cancelBtn', '#adminActionsTableWrapper');

    //Logi błędów
    bindSearchForm('#ErrorLogSearchForm', '#adminErrorLogsTableWrapper');
    bindAjaxKnpPaginator('#adminErrorLogsTableWrapper');
    bindAjaxModalForm('.btnErrorLogPreview', '#ErrorLogPreviewModal');
});