$(document).ready(function() {
    let sidebarTimer;
    const sidebar = $('.sidebar');
    const sidebarTrigger = $('.sidebar-trigger');
    const mainContent = $('.main-content');

    function showSidebar() {
        clearTimeout(sidebarTimer);
        sidebar.addClass('active');
        mainContent.addClass('sidebar-active');
    }

    function hideSidebar() {
        sidebarTimer = setTimeout(() => {
            sidebar.removeClass('active');
            mainContent.removeClass('sidebar-active');
        }, 300); // Delay to allow moving mouse to sidebar
    }

    sidebarTrigger.on('mouseenter', showSidebar);
    sidebarTrigger.on('mouseleave', hideSidebar);

    sidebar.on('mouseenter', showSidebar);
    sidebar.on('mouseleave', hideSidebar);
});