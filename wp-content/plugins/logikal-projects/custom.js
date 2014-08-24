
(function ($) {
    //Page defaults
    var //(Private)
        //gmapDefaultLocation = {latitude: 29.69752, longitude: 52.47027 },
        Reg_Email = /^\w+([\-\.]\w+)*@([a-z0-9]+(\-+[a-z0-9]+)?\.)+[a-z]{2,5}$/i,
        $window = $(window),
        isTouchDevice = 'ontouchstart' in window,
        transitionReady = supportsTransitions();

    //Utility functions

    function is_int(value) {
        if ((parseFloat(value) == parseInt(value)) && !isNaN(value)) {
            return true;
        } else {
            return false;
        }
    }

    //Detects if the browser supports transition
    function supportsTransitions() {
        var b = document.body || document.documentElement;
        var s = b.style;
        var p = 'transition';
        if (typeof s[p] == 'string') { return true; }

        // Tests for vendor specific prop
        v = ['Moz', 'Webkit', 'Khtml', 'O', 'ms'],
        p = p.charAt(0).toUpperCase() + p.substr(1);
        for (var i = 0; i < v.length; i++) {
            if (typeof s[v[i] + p] == 'string') { return true; }
        }
        return false;
    }

    /***** Back to top button ******/

    function BackToTopBtn() {
        $('#top_button').click(function () {
            $('html, body').animate({ scrollTop: 0 }, 400);
            return false;
        });
    }

    function IE_Fix() {

        if (!$.browser.msie) return;

        /***** Add input defaults Fix for IE ******/

        $('[placeholder]').focus(function () {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
                input.removeClass('placeholder');
            }
        }).blur(function () {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.addClass('placeholder');
                input.val(input.attr('placeholder'));
            }
        }).blur();


        /***** IE PNG Transparencity ******/

        $('.pngfix img,img.pngfix').each(function () {
            var $this = $(this);
            $this.css({ width: $this.width() + 'px', height: $this.height() + 'px', 'background-color': 'transparent' });
            this.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + this.src + "', sizingMethod='crop')";
            this.src = 'images/blank.gif';
        });

    }

    /***** Navigation ******/

    function Nav() {

        $('.navigation > ul').superfish({ animation: { opacity: 'show', height: 'show' }, delay: 500 });

        // Mobile Navigation

        $(document).click(
            function (e) {
                var $mobileNavBtn = $('.mobile-navigation > a');

                if (e.target != $mobileNavBtn.get(0) && $mobileNavBtn.hasClass('active'))
                    $mobileNavBtn.click();
            }
        );

        $('.mobile-navigation > a').click(function (e) {
            var $this = $(this),
                $menu = $this.parent().find('> ul');

            if ($this.hasClass('active')) {
                $menu.slideUp('fast');
                $this.removeClass('active');
            }
            else {
                $menu.slideDown('fast');
                $this.addClass('active');
            }

            e.preventDefault();
        });

    }
	
	/***** Alerts ******/
	
	function Alert() {
		$(".alert").click(function(){
			$(this).hide("slow");
		});
	} 
	
    /***** Google Map ******/

    function Gmap() {
        if ($('.contact_page').length < 1 || typeof gmapSettings == 'undefined')
            return;

        var $contactMap = $('<div class="contact_map"></div>'),
            $mapWrap = $('<div class="contact_map_wrap"></div>'),
            timerId = 0, location = null;

        $mapWrap.append($contactMap);
        $mapWrap.css({overflow:'hidden', width:'100%', height:'100%', position:'absolute'});

        $('body').prepend($mapWrap);


        $(window).resize(function () {
            clearTimeout(timerId);
            timerId = setTimeout(HandleResize, 100);
        }).resize();

        function HandleResize() {
            var height = 0, $body = $('body');
            $('.wrap').each(function () { height += $(this).outerHeight(); })

            if (height < $body.height())
                height = $body.height();

            $mapWrap.css({ height: height, width: $body.innerWidth() });

            if (location != null)
                $contactMap.gMap('centerAt', {
                    latitude: location.lat(),
                    longitude: location.lng(),
                    zoom: gmapSettings.zoom
                });
        }

        $contactMap.gMap(gmapSettings);
        
        var geocoder = new google.maps.Geocoder();

        // Check for address to center on
        // Get coordinates for given address and center the map
        geocoder.geocode(
            {
                address: gmapSettings.markers[0].address
            }, function (gresult, status) {
                if (gresult && gresult.length)
                    location = gresult[0].geometry.location;
            }
        );

    }

    /***** Blockquotes & Pullquotes ******/

    function Quotes() {
        $('blockquote,.pullquote,.pullquote_right').each(function () {
            $(this).append('<span class="end"></span>');
        });
    }


    /***** Toggle ******/

    function Toggle() {

        $('.toggle').each(function () {
            var $accordion = $(this),
                $title = $accordion.find('.toggle_title'),
                $content = $accordion.find('.toggle_content');


            //Close the accordion
            if ($accordion.hasClass('toggle_closed')) {
                $accordion.find('.toggle_content').hide();
            }
            else
                $accordion.find('.toggle_content').css({ display: 'block' });

            $title.click(function (e) {
                e.preventDefault();
                $content.slideToggle();
                $accordion.toggleClass('toggle_closed');
            });
        });

    }


    /***** Tabs ******/

    function Tabs() {

        if ($.fn.idTabs) {
            $('.tab .tab_head').idTabs(function (id, list, set) {
                $("a", set).removeClass("selected")
                .filter("[href='" + id + "']", set).addClass("selected");
                for (i in list)
                    $(list[i]).hide();
                $(id).fadeIn();
                return false;
            });
            $('.tab .tab_head li:last-child').addClass('tab_last');
        }

    }
	
	
	
	/***** Resume Form *****/
	
	function Resume() {

        var $respond = $('#resumeForm'), $respondWrap = $('#resumeForm_wrap'), $cancelCommentReply = $respond.find('#cancel-comment-reply-link'),
        $commentParent = $respond.find('input[name="comment_parent"]');
        
        $('.comment-reply-link').each(function () {
            var $this   = $(this),
                $parent = $this.parent().parent().next();

            $this.click(function () {
                var commId = $this.parents('.comment').find('.comment_id').html();

                $commentParent.val(commId);
                $respond.insertAfter($parent);
                $cancelCommentReply.show();

                return false;
            });
        });

        $cancelCommentReply.click(function (e) {
            $cancelCommentReply.hide();

            $respond.appendTo($respondWrap);
            $commentParent.val(0);

            e.preventDefault();
        });
        
        ResumeForm('#resumeForm');

    }//End Forms()
	
	
	function ResumeForm(formContainerId) {

        var $Resume = $(formContainerId);

        if ($Resume.length < 1)
            return;

        var $Form = $Resume.find('form'),
            IsResumeForm = $Form.hasClass('resume'),
            Action = $Form.attr('action'),
            $SubmitBtn = $Form.find('input[type="submit"]'),
            $submitWrap = $Form.find('.form-submit'),
            $Name = $Form.find('#name').val(),
			$Email = $Form.find('#email').val(),
			$Comment = $Form.find('#comment').val(),
			$Inputs = $Form.find('input[type="text"],textarea'),
			$Files = $Form.find('input[type="file"]'),
			$Loader = $Form.find('.loader'),
            $AjaxError = $Form.find('.AjaxError'),
            $AjaxComplete = $Form.find('.AjaxSuccess'),
            ValidFields = [$Inputs.length];
			
		
		
		//Uploader
		var values = $Form.serialize();
		var sender = $Name.toString();
	
		var files = $Files;
		
		var uploader = new plupload.Uploader({
			browse_button: 'browse',
			multipart_params: {
			email: $Email,
			sender: $Name,
			comment: $Comment
			
			},
			
			url:'/wp-content/themes/logikal/lib/sendResume.php'
		});

		uploader.init();
		var count = 1;
		
		
		
		uploader.bind('FilesAdded', function(up, files) {
		
		var html = '';
			
		plupload.each(files, function(file) {
			
			html += '<li id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></li>';
			count++
			
		});
		
		if(count >= 3){
			
			$("#fileBrowser").hide();
		}
		
		document.getElementById('filelist').innerHTML += html;
		
		$("#submit").attr('disabled','disabled');
		
		});
		
		uploader.bind('UploadProgress', function(up, file) {
		  $Loader.fadeIn('fast');
		  document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
		});

		uploader.bind('Error', function(up, err) {
		  $Loader.hide();
		  $AjaxError.fadeIn('fast');
		});

		uploader.bind('UploadComplete', function(up,files){
			$.each(files, function(index,value){
				$("form").append("<input type='hidden' name=filename[] value='"+value.name+"'>");
			})
			$Loader.hide();
			$('#filelist').css("display","none");
			$AjaxComplete.fadeIn('fast');
		});
			
			
		//Submit wrap
		if ($submitWrap.length) {
            $btnWrap = $('<div class="btn button_tailed"><div class="text">' + theme_strings.contact_submit + '</div><span></span></div>');
            $submitWrap.prepend($btnWrap);
            $SubmitBtn.val('');
            $btnWrap.prepend($SubmitBtn);
        }

        //Retry link
        $AjaxError.find('a').click(function (e) {
            $AjaxError.hide();
            $SubmitBtn.click();
            e.preventDefault();
        });

        //Handle form submission
        $SubmitBtn.click(function (e) {
            var IsValid = true;

            $Inputs.blur();

            //Check if all fields are valid
            $.each(ValidFields, function (i, v) {
                if (v == false) {
                    IsValid = false;
                    return false;
                }
            });

            if (!IsValid) {
                e.preventDefault();
                return;
            }

            //No need to continue the submission process
            if (!IsResumeForm)
                return;

            //Show progress bar
            $Loader.fadeIn('fast');
            //Prevent multi clicking
            $SubmitBtn.parent().fadeOut('fast');
			
			var $Name = $Form.find('#name').val(),
			$Email = $Form.find('#email').val(),
			$Comment = $Form.find('#comment').val()
			
			uploader.bind('BeforeUpload', function(up)
    		{
    			up.settings.multipart_params =
    		{
       		 email: $Email,
			 sender: $Name,
			 comment: $Comment
    		};

			});

			uploader.start();
			
            e.preventDefault();
        });

        //Handle Controls Lost Focus Event
        $Inputs.each(function (i) {
            var $me = $(this),
                type = $me.attr('name'),
                DefaultVal = $me.attr('placeholder'),
                $Error = $Resume.find('.' + type + 'Error');
			
			
            if (typeof DefaultVal == 'undefined')
                DefaultVal = '';

            //Control lost focus
            $me.blur(function () {
                var Value = $.trim($me.val()),
                    isValid = true;

                //Validate by type
                if (type == 'email') {
                    if (!Reg_Email.test(Value) || Value == DefaultVal) {
                        isValid = false;
                    }
                }
                else if (type == 'name' || type == 'surname') {
                    if (Value.length < 1 || Value.length > 50 || Value == DefaultVal) {
                        isValid = false;
                    }
                }
                else if (type == 'comment') {
                    if (Value.length < 1 || Value.length > 1000) {
                        isValid = false;
                    }
                }

                if (!isValid) {
                    $Error.fadeIn('fast');
                    ValidFields[i] = false;
                }
                else {
                    $Error.fadeOut('fast');
                    ValidFields[i] = true;
                }

            }); //$me.blur
        });

    }//End ResumeForm

	
	
    /***** Comment & Contact Forms ******/

    function Forms() {

        var $respond = $('#respond'), $respondWrap = $('#respond_wrap'), $cancelCommentReply = $respond.find('#cancel-comment-reply-link'),
        $commentParent = $respond.find('input[name="comment_parent"]');
        
        $('.comment-reply-link').each(function () {
            var $this   = $(this),
                $parent = $this.parent().parent().next();

            $this.click(function () {
                var commId = $this.parents('.comment').find('.comment_id').html();

                $commentParent.val(commId);
                $respond.insertAfter($parent);
                $cancelCommentReply.show();

                return false;
            });
        });

        $cancelCommentReply.click(function (e) {
            $cancelCommentReply.hide();

            $respond.appendTo($respondWrap);
            $commentParent.val(0);

            e.preventDefault();
        });
        
        ContactForm('#respond');

    }//End Forms()


    function ContactForm(formContainerId) {

        var $Contact = $(formContainerId);

        if ($Contact.length < 1)
            return;

        var $Form = $Contact.find('form'),
            IsContactForm = $Form.hasClass('contact'),
            Action = $Form.attr('action'),
            $SubmitBtn = $Form.find('input[type="submit"]'),
            $submitWrap = $Form.find('.form-submit'),
            $Inputs = $Form.find('input[type="text"],textarea'),
			
            $Loader = $Form.find('.loader'),
            $AjaxError = $Form.find('.AjaxError'),
            $AjaxComplete = $Form.find('.AjaxSuccess'),
            ValidFields = [$Inputs.length];
			
        if ($submitWrap.length) {
            $btnWrap = $('<div class="btn button_tailed"><div class="text">' + theme_strings.contact_submit + '</div><span></span></div>');
            $submitWrap.prepend($btnWrap);
            $SubmitBtn.val('');
            $btnWrap.prepend($SubmitBtn);
        }

        //Retry link
        $AjaxError.find('a').click(function (e) {
            $AjaxError.hide();
            $SubmitBtn.click();
            e.preventDefault();
        });

        //Handle form submission
        $SubmitBtn.click(function (e) {
            var IsValid = true;

            $Inputs.blur();

            //Check if all fields are valid
            $.each(ValidFields, function (i, v) {
                if (v == false) {
                    IsValid = false;
                    return false;
                }
            });

            if (!IsValid) {
                e.preventDefault();
                return;
            }

            //No need to continue the submission process
            if (!IsContactForm)
                return;

            var values = $Form.serialize();

            //Show progress bar
            $Loader.fadeIn('fast');
            //Prevent multi clicking
            $SubmitBtn.parent().fadeOut('fast');

            //Send post request
            $.ajax({
                type: "POST",
                url: Action,
                data: values,
                error: function (xhr, error) {
                    $Loader.hide();
                    $AjaxError.fadeIn('fast');
                },
                success: function (msg) {
                    $Loader.hide();
                
                    $AjaxComplete.fadeIn('fast');
                   
                }
            });

            e.preventDefault();
        });

        //Handle Controls Lost Focus Event
        $Inputs.each(function (i) {
            var $me = $(this),
                type = $me.attr('name'),
                DefaultVal = $me.attr('placeholder'),
                $Error = $Contact.find('.' + type + 'Error');

            if (typeof DefaultVal == 'undefined')
                DefaultVal = '';

            //Control lost focus
            $me.blur(function () {
                var Value = $.trim($me.val()),
                    isValid = true;

                //Validate by type
                if (type == 'email') {
                    if (!Reg_Email.test(Value) || Value == DefaultVal) {
                        isValid = false;
                    }
                }
                else if (type == 'name' || type == 'surname') {
                    if (Value.length < 1 || Value.length > 50 || Value == DefaultVal) {
                        isValid = false;
                    }
                }
                else if (type == 'comment') {
                    if (Value.length < 1 || Value.length > 1000) {
                        isValid = false;
                    }
                }

                if (!isValid) {
                    $Error.fadeIn('fast');
                    ValidFields[i] = false;
                }
                else {
                    $Error.fadeOut('fast');
                    ValidFields[i] = true;
                }

            }); //$me.blur
        });

    }//End ContactForm

    /***** About Template Wrap container Resize ******/

    function AboutUs() {

        if ($('.page-template-template-about-php').length)
            $window.resize(function () {
                var $wrap = $('#wrap_main'),
                    w = $window.width();

                if (w < 768) {
                    $wrap.css('margin-top', w * 0.4);
                }
                else {
                    //0.296875 ratio
                    $wrap.css('margin-top', w * 0.296875);
                }

        }).resize();
    }

    /***** Page background ******/

    function PageBackground() {
        var $img = $(".page-background img");

        if (!$img.length)
            return;

        var img = $img[0], // Get my img elem
            real_width,
            real_height;

        $("<img/>") // Make in memory copy of image to avoid css issues
            .attr("src", $(img).attr("src"))
            .load(function () {
                // Note: $(this).width() will not
                // work for in memory images.
                $img.attr('data-width', this.width);
                $img.attr('data-height', this.height);
                $img.attr('data-aspectratio', this.width / this.height);
                $img.css('max-width', 'none');

                PageBgResizeHandler();
            });

        $window.resize(PageBgResizeHandler);

        function PageBgResizeHandler() {

            var $bg = $('.page-background img'),
                $wrap = $('#wrap_main'),
                w = $window.width(),
                minH768 = 400;

            if (w < 768) {
                var realImgW = parseInt($bg.attr('data-width')),
                    realImgH = parseInt($bg.attr('data-height')),
                    ratio = parseFloat($bg.attr('data-aspectratio')),
                    imgW = ratio * minH768;

                if (imgW >= w) {
                    var margin = (w - imgW) * 0.5;
                    $bg.css({ width: imgW, 'left': margin });
                } else {
                    $bg.css({ width: '100%', 'left': 0 });
                }
            }
            else {
                $bg.css({ width: '100%', 'left': 0 });
            }

        }//End PageBgResizeHandler()

    }


    /***** Page Load More Function ******/

    function Ajax_Load_Page() {

        var $pageNav = $('.page-navigation'), 
            $blog    = $('#posts');

        if (typeof paged_data == 'undefined' || $pageNav.length < 1) 
            return;
        
        var startPage = parseInt(paged_data.startPage),
            nextPage  = startPage + 1,
            max       = parseInt(paged_data.maxPages),
            isLoading = false;

        if (max < 2) return;

        //Replace links with load more button
        $pageNav.html('<button class="btn button_tailed"><span class="text">' + paged_data.loadMoreText + '</span><span class="tail"></span></button>');
        var $btn = $pageNav.find('button'),
            $btnText = $btn.find('.text');

        if (nextPage > max) 
            $btnText.text(paged_data.noMorePostsText);
        
        var resTimer = 0;
        $window.resize(function () {
            clearTimeout(resTimer);
            resTimer = setTimeout(function () {
                if ($window.width() < 768) {
                    $pageNav.insertAfter($blog);
                }
                else {
                    $pageNav.insertAfter($blog.parent());
                }
            }, 100);
        }).resize();

        $btn.click(function () {
            if (nextPage > max || isLoading)
                return;

            isLoading = true;

            //Set loading text
            $btnText.text(paged_data.loadingText);

            var $pageContainer = $('<div class="posts-page-'+nextPage+'"></div>');

            $pageContainer.load(paged_data.nextLink + ' .post', function () {

                //Insert the posts container before the load more button
                $pageContainer.appendTo($blog);

                // Update page number and nextLink.
                nextPage++;

                if (/\/page\/[0-9]+/.test(paged_data.nextLink))
                    paged_data.nextLink = paged_data.nextLink.replace(/\/page\/[0-9]+/, '/page/' + nextPage);
                else
                    paged_data.nextLink = paged_data.nextLink.replace(/paged=[0-9]+/, 'paged=' + nextPage);

                if (nextPage <= max)
                    $btnText.text(paged_data.loadMoreText);
                else if (nextPage > max)
                    $btnText.text(paged_data.noMorePostsText);

                isLoading = false;
            });
            

        });
    }

    function Portfolio() {
        var $container = $('.isotope');

        if ($container.length < 1)
            return;

        $container.isotope({
            // options
            itemSelector: '.item',
            layoutMode: 'masonry',
            animationEngine: 'best-available'
        });


        // filter items when filter link is clicked
        $('.subnavigation a').click(function (e) {
            e.preventDefault();

            var $this = $(this);

            if ($this.hasClass('.current')) 
                return;
            
            var $optionSet = $this.parents('.subnavigation');

            $optionSet.find('.current').removeClass('current');
            $this.addClass('current');
            var selector = $(this).attr('data-filter');
            $container.isotope({ filter: selector });
        });


        // changing portfolio views
        $('#portfolio_styles a').click(function (e) {
            e.preventDefault();

            var $this = $(this);

            if ($this.hasClass('current')) 
                return;

            var $optionSet = $this.parents('#portfolio_styles');
            $optionSet.find('.current').removeClass('current');
            $this.addClass('current');
            $container.toggleClass('gallery_fix');
            $container.isotope('reLayout');
        });

        HandleItemMeta($('.gallery .item'), 
            function (e) {
                var $target = $(e.target);

                if ($target.hasClass('item_meta') || $target.parents('.item_meta').length)
                    return false;
            }
            , false);

        //Pretty Photo Plugin

        var prettyPhotoMode = 0;//Enabled

        if(typeof page_settings != 'undefined')
            prettyPhotoMode = parseInt(page_settings.prettyphoto_mode);

        var $gallery = $('.gallery .item_image');

        if( prettyPhotoMode == 1 ||
            (prettyPhotoMode == 2 && isTouchDevice) ||
            (prettyPhotoMode == 3 && !isTouchDevice) ||
            !$.fn.prettyPhoto)
        {
            //Change item links to detail page links if possible
            $gallery.each(function(){
                var $lnk = $(this),
                    $parent = $lnk.parent(),
                    $detailLnk = $parent.find('a.item_meta');

                if(!$detailLnk.length)
                    return;

                $lnk.attr('href', $detailLnk.attr('href'));
            });
            return;
        }

        //Pretty photo handling

        $().prettyPhoto({ theme: 'dark_square' });

        $gallery.click(function (e) {
            var $item = $(this),
                href = $item.attr('href'),
                title = $item.attr('title');

            $.prettyPhoto.open(href, title, '');

            e.preventDefault();
        });

    }

    function PortfolioSingle() {
        var $container = $('.portfolio_single .touch-slider');

        if (!$container.length)
            return;

        if ($.fn.touchSlider)
            $container.touchSlider({ display: 'fill' });

        var jctimer,  $mycarousel = $('#mycarousel');

        //if ($mycarousel.children().length < 2)
            //return;

        HandleItemMeta($mycarousel.find('.item'), function (e) {
            var $target = $(e.target);

            if ($target.hasClass('item_meta') || $target.parents('.item_meta').length)
                return false;

        }, false);

        $window.resize(function () {
            clearTimeout(jctimer);
            jctimer = setTimeout(carouselSetSize, 100);
        }).resize();

        function carouselSetSize() {

            var width = $window.width(),
                settings = {
                    scroll: 1, visible: 1, animation: 300,
                    easing: 'easeOutCubic',//Wrap mode has bug
                    auto: 0
                };

            // Configuration for screen Size with less than 480px
            if (width < 480) {

            }
            // Configuration for screen Size with less than 768px							
            else if (width < 768) {
                settings.visible = 2;
            }
                // Configuration for screen Size with less than 1024px							
            else if (width < 1024) {
                settings.visible = 3;
            }
            else {
                settings.visible = 4;
                settings.scroll = 4;
            }

            $mycarousel.jcarousel(settings);
        }

    }

    function HandleItemMeta($items, clickCallback, showCallback) {
        
        function ShowMeta($meta, $item) {
            $meta.css({ bottom: 0 });
        }

        function HideMeta($meta, $item) {
            $meta.css({ bottom: -$item.height() });
        }


        $items.each(function () {
            var $item = $(this),
                $meta = $item.find('.item_hover'),
                metaOn = false;

            $meta.removeClass('item_hover');

            $item.hover(
                function () {
                    if ($window.width() < 1024) return;

                    HideMeta($meta, $item);//Fix webkit browser bug

                    if (typeof showCallback == 'function' )
                        showCallback($meta, $item);
                    else
                        ShowMeta($meta, $item);
                },
                function () {
                    if ($window.width() < 1024) return;

                    HideMeta($meta, $item);
                });

            //Activate item meta by click/tap on mobile browsers
            $item.click(HandleClick);

            function HandleClick(e) {
                var ret = true;

                if (typeof clickCallback == 'function')
                    ret = clickCallback(e);

                if (typeof ret != 'undefined' && ret === false)
                    return;

                //Don't run on normal devices with mouse
                if (!isTouchDevice) return;

                e.preventDefault();

                if (!metaOn) {
                    HideMeta($meta, $item);

                    if (typeof showCallback == 'function')
                        showCallback($meta, $item);
                    else
                        ShowMeta($meta, $item);
                }
                else HideMeta($meta, $item);

                metaOn = !metaOn;
            }

        });
    }

    function IntroBox() {
        $(".special_intro .titles a").click(function (e) {
            e.preventDefault();

            // Set variables and index of selected item.
            var $this = $(this);
            var index = $this.parent().index();

            // Set selected item as selected.
            $(".special_intro .titles a").removeClass("selected");
            $this.addClass("selected");

            // Calculate the height of the negative margin needed to show desired text.
            var top = (index * $(".agency .special_intro .descriptions").height());
            $(".agency .special_intro .descriptions > div").animate({ marginTop: -(top) }, 250);

            // Calculate position of arrow to match selected item.
            top = 18 + (index * ($this.height() + 20));

            $(".agency .special_intro .line").animate({ backgroundPosition: "0 " + top + "px" }, 400);

        });
    }

    function About() {
        //Intro Box
        IntroBox();

        var jctimer, $mycarousel = $('.page-template-template-about-php #mycarousel');

        if (!$mycarousel.length)
            return;

        //Item meta
        HandleItemMeta($mycarousel.find('.item'), function (e) {
                var $target = $(e.target);

                if ($target.hasClass('item_meta') || $target.parents('.item_meta').length)
                    return false;
            },
            function ($meta, $item) {
                bottom = Math.floor(($item.height() - $meta.outerHeight()) * 0.5);
                $meta.css({ bottom: bottom });
            });


        //jCarousel Resize

        $window.resize(function () {
            clearTimeout(jctimer);
            jctimer = setTimeout(carouselSetSize, 100);
        }).resize();

        function carouselSetSize() {

            var width = $window.width(),
                settings = {
                    scroll: 1, visible: 1, animation: 300,
                    easing: 'easeOutCubic',//Wrap mode has bug
                    auto: 0
                };

            // Configuration for screen Size with less than 480px
            if (width < 480) {

            }
            // Configuration for screen Size with less than 768px							
            else if (width < 768) {
                settings.visible = 2;
            }
            // Configuration for screen Size with less than 1024px							
            else if (width < 1024) {
                settings.visible = 3;
            }
            else {
                settings.visible = 3;
                settings.scroll = 3;
            }

            $mycarousel.jcarousel(settings);
        }
    }

    function Home() {
        if (!$('.page-template-template-home-php').length)
            return;

        var $sliders = $('.portfolio-slider');

        $sliders.touchSlider({
            frame_height: 340, crop_mode: true, onSlideChange: function (i) {
                var $meta = this.find('.item_meta');

                if (i == 0) {
                    if (transitionReady)
                        $meta.css('bottom', 0);
                    else
                        $meta.stop().animate({bottom:0}, {speed:300});
                }
                else
                    if (transitionReady)
                        $meta.css('bottom', -$meta.outerHeight());
                    else
                        $meta.stop().animate({ bottom: -$meta.outerHeight() }, { speed: 300 });
            }
        });
    }

    function GalleryShortcode() {
        var $gallery = $('.gallery .gallery-icon a');

        $gallery.attr('rel', 'prettyPhoto');

        if ($.fn.prettyPhoto)
            $gallery.prettyPhoto({ theme: 'dark_square' });
    }

    function AwesomeSlider()
    {
        if(!$.fn.awesomeSlider)
            return;

        var $slider = $('.awesome-slider');

        if (typeof slider_settings != 'undefined')
            $slider.awesomeSlider({
                delay: parseInt(slider_settings.delay, 10),
                itemsAnimateInSpeed: parseInt(slider_settings.itemInSpeed, 10),
                itemsAnimateOutSpeed: parseInt(slider_settings.itemOutSpeed, 10),
                bgFadeInSpeed: parseInt(slider_settings.bgSpeed, 10),
                bgFadeOutSpeed: parseInt(slider_settings.bgSpeed, 10)
            });
        else
            $slider.awesomeSlider();
    }

    //Call initializers on document ready
    $(document).ready(function () {

        GalleryShortcode();
        Alert();
        Gmap();
        BackToTopBtn();
        IE_Fix();
        Nav();
        Quotes();
        Tabs();
        Forms();
		Resume();
        AboutUs();
        Toggle();
        PageBackground();
        Ajax_Load_Page();
        Portfolio();
        PortfolioSingle();
        About();
        Home();
        AwesomeSlider();

    });//End $(document).ready

})(jQuery);


//Dummy object 
var addComment = {
    moveForm: function () { }
};
