type="text/javascript";
jQuery(document).ready(function($) {
    let keys = 0;
    const formContainer = $('#searchform-container');
    const searchForm = $('#aw-searchform');
    const searchInput = searchForm.find('input#s');
    const searchFilter = $('#searchform-filter-button');
    const searchOptions = $('#search-filter-options');
    const filterButtons = $('option.filter-button');
    const submitButton = $('#searchform-submit');
    const searchWrapper = $('#searchform-dropdown-wrapper');
    const searchResults = $('#searchform-results-dropdown');
    // const resultsContainer = $('#searchform-results-content');

    $(searchWrapper).hide();
    //$(searchResults).hide();

    const searchIcon = $('#search-icon');
    const dropdownLoading = $('#dropdown-loading');
    const pageLoading = $('#page-loading');
    $(dropdownLoading).hide();
    $(pageLoading).hide();


    $(searchInput).attr('autocomplete', 'off');

    const getExecutedValue = (parent) => {
        let executed;
        const children = $(parent).children();
        //console.log('children', children);
        if(children.length === 0){
            executed = false;
            //console.log('executed => false');
        }
        if(children.length > 0) {
            executed = true;
            //console.log('executed => true');
        }
        return executed
    }
    const resetSearch = (parent) => {
        searchTerm = '';
        $(parent).empty();
        executed = false;
        keys = 0;
        //console.log('reset search');
        return keys
    }
    const addLoading = (parent) => {
        // add loading information box
        if( $('#loading').css('display') !== 'block' ){
            if( $('#loading').css('display') === 'none' ){
                $('#loading').css('display', 'block');
            } else {
                const loading = document.createElement('div');
                $(loading).attr('id', 'loading');
                const text = document.createElement('p');
                $(text).html('  Loading search results, please wait ...  ');
                $(loading)[0].appendChild(text);
                $(parent)[0].appendChild(loading);
            }
        }
    }


    const addSearchErr = (parent, query, options) => {
        if($('#search-err').css('display') !== 'block'){
            if($('#search-err').css('display') === 'none'){
                $('#search-err').css('display', 'block');
            } else {
                const text = document.createElement('p');
                $(text).attr('id', 'search-err');
                $(text).css('display', 'block');
                $(text).html(`No search results for '${query}' in '${options}'.`);
                //$(text).html('Error with search!');
                $(parent)[0].appendChild(text);
            }
        }//end if
    }

    //$(searchOptions).hide();
    const span = $(searchFilter).find('span');

    $(span).on('click', (e) => {
        //console.log('filter click');
        $(e).preventDefault;
        $(filterButtons).show();
        $(searchOptions).show();
        $(searchOptions).click();
        const children = $(searchOptions).children();
        $(children).each((i, elm) => {
            $(elm).show();
        })
    });

    // const setAllCategories = () => {
    //     const buttons = $(filterButtons);
    //     $(buttons).each((i, elm) => {
    //         const status = $(elm).attr('data-value');
    //         if(status === 'all'){
    //             $(elm).attr('status', 'selected');
    //         }
    //         else {
    //             $(elm).attr('status', 'not-selected');
    //         }
    //     });
    // }
    // setAllCategories();

    // const getSelected = () => {
    //     const buttons = $(filterButtons);
    //     const selected = $('a[status=selected]');
    //     return selected
    // }
    //
    // $(filterButtons).click(e => {
    //     $(e).preventDefault;
    //     const selected = getSelected();
    //     $(selected).attr('status', 'not-selected');
    //     const target = e.target;
    //     $(target).attr('status', 'selected');
    // })
    //
    // const getSelectedOption = () => {
    //     let option;
    //     const children = $(searchOptions).children();
    //     //console.log('children inside selectedoption', children);
    //     $(children).each((i, elm) => {
    //         //console.log('elm', elm);
    //         const status = $(elm).attr('status');
    //         //console.log('status', status);
    //         if(status === 'selected'){
    //             const value = $(elm).attr('data-value');
    //             //console.log('value', value);
    //             option = value;
    //         }
    //     })
    //     return option
    // }

    // $(searchForm).on('submit', (e) => {
    //     const resultsContainer = searchResults.find('#searchpage-results-content');
    //     $(resultsContainer).empty();
    //     $('#searchpage-results-content').empty();
    //     $(searchResults).empty();
    //     $(searchWrapper).empty();
    // })



    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //
    //             IF STATEMENT
    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

    if( $('body').hasClass('search') ){
        //console.log('is search page');
        $(searchWrapper).empty();
        $(searchWrapper).css('display', 'none');
        $(searchWrapper).hide();
        $(searchResults).empty();
        $(searchResults).css('display', 'none');

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //
        //             SEARCH PAGE
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

        const searchPage = $('body.search');
        const ajaxResults = $('#searchpage-results-content');

        const doSearchPage = () => {
            //console.log('doSearchPage');
            let executed = false;
            const query = searchInput.val();
            //const option = getSelectedOption();
            const select = $('select#search-filter-options');
            const option = $(select).children("option:selected").val();
            if(executed === true){
                //console.log('skip search');
                return
            } else if( executed === false ){
                $.ajax({
                    type: 'post',
                    url: myAjax.ajaxurl,
                    data: {
                        action: 'aw_update_search_results_page',
                        option: option,
                        query: query
                    },
                    beforeSend: function() {
                        $(ajaxResults).children().empty();
                        $(pageLoading).show();
                    },
                    success: function( res ) {
                        //console.log(res);
                        if( executed === false){
                            $(pageLoading).hide();
                            //searchInput.prop('disabled', false);
                            const container = document.createElement('div');
                            const c = $(ajaxResults).children();
                            if(c.length>0){
                                $(ajaxResults).empty();
                                $(ajaxResults)[0].appendChild(container);
                            }
                            if(keys===0){
                                $(ajaxResults)[0].appendChild(container);
                                keys++;
                            }
                            $(container).html( res );
                            //console.log('sucess!');
                        }
                        executed = true;
                    },
                    error: function(jqxhr, status, errorThrown) {
                        //searchInput.prop('disabled', false);
                        if( $('#loading').css('display') === 'block' ){
                           $('#loading').css('display', 'none');
                        }
                        console.log(status);
                        console.log('error!', errorThrown);
                        addSearchErr(searchResults, query, option);
                    }

                }); // end ajax call
                return false
            } // end else
        } // end do search


        $(searchForm).on('keyup', (e) => {
            //console.log('search-page');
            // let executed = getExecutedValue(searchResults);
            // console.log('inside keyup executed = ', executed);
            //     if(executed === true){
            //         console.log('skip search');
            //     } if( executed === false ){
                    doSearchPage();
                //}
        });

        $(searchOptions).on('change', (e) => {
            //console.log('change');
            doSearchPage();
        })

        //showOptionsFilter(  );
    }
    else {
        //console.log('not search page');
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //
        //             SEARCH FORM
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

        $(searchResults).css('display', 'block');
        const resultsContainer = $('#searchform-results-content');
        const ajaxResults = resultsContainer.find('#search-results');

         $(ajaxResults).empty();
         $(resultsContainer).empty();
         $(searchResults).empty();

        const doSearch = () => {
            //console.log('do search');
            let executed = false;
            const query = searchInput.val();
            //console.log(query);
            const select = $('select#search-filter-options');
            const option = $(select).children("option:selected").val();
            //console.log(option);
            //const option = getSelectedOption();
            if(executed === true){
                //console.log('skip search');
                return
            } else if( executed === false ){
                $.ajax({
                    type: 'post',
                    url: myAjax.ajaxurl,
                    data: {
                        action: 'aw_show_search_results',
                        option: option,
                        query: query
                    },
                    beforeSend: function() {
                        $(resultsContainer).show();
                        $(resultsContainer).css('display', 'block');
                        $(ajaxResults).empty();
                        $(resultsContainer).empty();
                        $(searchResults).hide();
                        $(searchIcon).hide();
                        $(dropdownLoading).show();
                        //addLoading(searchWrapper);
                    },
                    success: function( res ) {
                        //console.log(res);
                        $(searchResults).show();
                        $(resultsContainer).show();
                        $(resultsContainer).css('display', 'block');
                        if( executed === false){
                            $(dropdownLoading).hide();
                            $(searchIcon).show();
                            $(searchWrapper).show();
                            if( $(searchWrapper).css('display') === 'none' ){
                                $(searchWrapper).css('display', 'block');
                                //$(searchResults).css('display', 'block');
                            }
                            //
                            searchInput.prop('disabled', false);
                            const container = document.createElement('div');
                            const c = $('#search-results').children();
                            //console.log('children', c);
                            if(c.length>0){
                                $(searchResults).empty();
                                $(searchResults)[0].appendChild(container);
                            }
                            if(keys===0){
                                $(searchResults)[0].appendChild(container);
                                keys++;
                            }
                            $(container).html( res );
                            //$(searchResults).html( res );
                            //console.log('sucess!');
                        }
                        executed = true;

                    },
                    error: function(jqxhr, status, errorThrown) {
                        searchInput.prop('disabled', false);
                        if( $('#loading').css('display') === 'block' ){
                            $('#loading').css('display', 'none');
                        }
                        console.log(status);
                        console.log('error!', errorThrown);
                        //addSearchErr(searchResults, query, option);
                    }
                });
                return false
            }
        } // end do search

        $(searchInput).on('keyup', (e) => {
            $(dropdownLoading).hide();
            let executed = getExecutedValue(ajaxResults);
            //console.log('inside keyup executed = ', executed);
                if(executed === true){
                    //console.log('skip search');
                } if( executed === false ){
                    doSearch();
                }
        });
        $(searchInput).on('keydown', (e) => {
            //console.log('keydown');
            $(dropdownLoading).hide();
            //remove dropdown if enter is pressed
            if( e.keyCode === 13){
                //console.log('submit enter');
                if( $(searchWrapper).css('display') === 'block' ){
                    $(searchWrapper).css('display', 'none');
                }
            }
        });
        // $(searchInput).on('click', function(e){
        //     if( $(searchOptions).css('display') === 'block' ){
        //         $(searchOptions).css('display', 'none');
        //     }
        // });

        $('#main').on('click', function(e){
            if( $(searchWrapper).css('display') === 'block' ){
                $(searchWrapper).css('display', 'none');
            }
        });

        $(document).on('keydown', (e) => {
            //console.log(e.keyCode);
            if(e.keyCode === 27) {
                if( $(searchWrapper).css('display') === 'block' ){
                    $(searchWrapper).css('display', 'none');
                }
            }
        });
        //showOptionsFilter( );

        $(searchOptions).on('change', (e) => {
            //console.log('change');
            doSearch();
        })


    } // end else search form


})
