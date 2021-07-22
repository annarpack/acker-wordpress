type="text/javascript";
jQuery(document).ready(function($) {

    const topContainer = $('body').find('div.menu-top-menu-container');
    const topMenu = $(topContainer).find('ul#menu-top-menu');
    const subMenu = $(topMenu).find('ul.sub-menu');

    $(subMenu).hide();

    $(topMenu).click( e => {
        const t = e.target;
        e.preventDefault();
        $(subMenu).toggle();
    });

    $(document).on('scroll', (e) => {
        $(subMenu).hide();
    })


});
