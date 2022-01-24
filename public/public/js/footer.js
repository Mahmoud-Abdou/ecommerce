

        const baseURl = 'https://market.a-gh.com/api/';
        const header = {
            'language': 'en',
            'Content-Type': 'application/json'
        };
 
        // start discounts and trendy 
        (function getdiscounts() {
            $.ajax({
                url: baseURl + "hekaya-home",
                method: 'GET',
                headers: header,
                success: function (response) {
                    $('#discount-products').html('');
                    $('#trendy-products').html('');
                    // console.log($('#caption1').text()) ;
                  
             
                    // start discounts
                    response.data.discounts.forEach(element => {
                        // console.table(element);
                        //  console.log(image);
                        if (element.discount_price != 0) {
                            $('#discount-products').append(

                                `
                                            <div class="col-6 col-md-6 col-lg-6 col-xl-2" >
                                                            <div class="product-default inner-quickview inner-icon">
                                                                <figure>
                                                                    <a href="product.html?id=${element.id}">
                                                                        <img src="${element.media[0].url}" style="max-width: 100%;">
                                                                    </a>
                                                                    <div class="label-group">
                                                                        <span class="product-label label-cut">${Math.round(element.discount_price / element.price * 100)}%</span>
                                                                    </div>
                                                                    <div class="btn-icon-group">
                                                                        <button class="btn-icon btn-add-cart" data-toggle="modal" data-target="#addCartModal">
                                                                            <i class="icon-bag"></i>
                                                                        </button>
                                                                    </div>
                                                                    <a href="product.html?id=${element.id}" class="btn-quickview" title="Quick View">Quick View</a>
                                                                </figure>
                                                                <div class="product-details">
                                                                    <div class="category-wrap">
                                                                        <div class="category-list">
                                                                            <a href="category.html" class="product-category">category</a>
                                                                        </div>
                                                                    </div>
                                                                    <h2 class="product-title">
                                                                        <a href="product.html?id=${element.id}">${element.name}</a>
                                                                    </h2>
                                                                    <div class="ratings-container">
                                                                        <div class="product-ratings">
                                                                            <span class="ratings" style="width:100%"></span>
                                                                            <!-- End .ratings -->
                                                                            <span class="tooltiptext tooltip-top"></span>
                                                                        </div>
                                                                        <!-- End .product-ratings -->
                                                                    </div>
                                                                    <!-- End .product-container -->
                                                                    <div class="price-box">
                                                                        <span class="old-price">${element.discount_price}</span>
                                                                        <span class="product-price">${element.price}</span>
                                                                    </div>
                                                                    <!-- End .price-box -->
                                                                </div>
                                                                <!-- End .product-details -->
                                                            </div>
                                                        </div>

                                            `
                            );

                        }
                    });
                    // end discounts
                    // start trendy
                    response.data.trending.forEach(element => {
                        // console.table(element);
                        //  console.log(image);
                        // if (element.discount_price != 0) {
                        $('#trendy-products').append(

                            `
                                            <div class="col-6 col-lg-3" style="border-radius:7px;">
                                                <div class="home-banner">
                                                    <img src="${element.media[0].url}" style="width:100%;height:250px;border-radius:7px;">
                                                    <div class="home-banner-content content-left-bottom">
                                                        <h3>${element.name}</h3>
                                                        <h4>${element.description}</h4>
                                                        <a href="category.html" class="btn" role="button">Shop By Glasses</a>
                                                    </div>
                                                </div>
                                            </div>

                                            `
                        );

                        // }
                    });
                    // end trendy


                   
                }

            })
        })();
        // end discounts and trendy 

        // start main category
        (function getCategories() {
            $.ajax({
                url: baseURl + "categories",
                method: 'GET',
                headers: header,

                success: function (response) {
                    $('#main-category').html('');
                    response.data.forEach(element => {
                        // console.table(element.media[0].url);
                        // start header-footer-mobile categories
                        $('#header-categories').append(`<li><a href="category.html">${element.name}</a></li>`);
                        $('#header-category-image').html('');
                        $('#header-category-image').append(` <img src="${response.data[0].media[0].url}" align="Menu banner">`);
                        // $('#footer-categories').html('');
                        $('#footer-categories').append(`<li><a href="#">${element.name}</a></li>`);
                        // $('#mobile-nav').html('');
                        $('#mobile-nav').append(` <li><a href="category.html">${element.name}</a> </li>`);

                        // End header-footer-mobile categories
                        if (element.parent_id == 0) {
                            $('#main-category').append(`
                                        <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                                            <div class="product-category">
                                                <a href="category.html">
                                                    <figure>
                                                        <img src="${element.media[0].url}" style="width:100%;height:200px">
                                                    </figure>
                                                    <div class="category-content">
                                                        <h3>${element.name}</h3>
                                                       
                                                    </div>
                                                </a>
                                            </div>
                                        </div>`);

                        }



                    })

                }

            })
        })();
        // end main category


        
        // start main category
        (function getMarkets() {
            $.ajax({
                url: baseURl + "markets",
                method: 'GET',
                headers: header,

                success: function (response) {
                    $('#main-markets').html('');
                    response.data.forEach(element => {
                       console.table(element);
               
                        // End header-footer-mobile categories
                    
                            $('#main-markets').append(`
                                        <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                                            <div class="product-category">
                                                <a href="category.html">
                                                    <figure>
                                                        <img src="${element.media[0].url}" style="width:100%;height:200px">
                                                    </figure>
                                                    <div class="category-content">
                                                        <h3>${element.name}</h3>
                                                       
                                                    </div>
                                                </a>
                                            </div>
                                        </div>`);

                        



                    })

                }

            })
        })();
        // end main category


        // start product details 
                 // Read a page's GET URL variables and return them as an associative array.
                function getUrlVars()
                {
                    var vars = [], hash;
                    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                    for(var i = 0; i < hashes.length; i++)
                    {
                        hash = hashes[i].split('=');
                        vars.push(hash[0]);
                        vars[hash[0]] = hash[1];
                    }
                    return vars;
                }
                 let productID= getUrlVars()["id"];

                 (function getProductDetails() {
                    $.ajax({
                        url: baseURl + `products/${productID}?with=market;category;options;optionGroups;productReviews;productReviews.user`,
                        method: 'GET',
                        headers: header,
        
                        success: function (response) {
                            console.table(response.data);

                            $('#breadcrumb-catName').html(response.data.category.name);
                            $('#breadcrumb-productName').html(response.data.name);
                            $('#product-title').html(response.data.name);
                            $('#product-old-price').html(response.data.discount_price);
                            $('#product-price').html(response.data.price);
                            $('#product-desc').html(response.data.description);
                            var productslides=`
                                <div class="product-item" id="product-item-image" >
                                    <img class="product-single-image"  src="${response.data.media[0].url}" data-zoom-image="${response.data.media[0].url}"/>
                                </div>
                            
                              
                  
                              `;
                            //   $('#addc').html(productslides);
                        

                //     let customdotss=`<img id="owl-dot-item-image" src="assets/images/products/zoom/product-1.jpg"/>
                // </div>
                // <div class="col-3 owl-dot" id="owl-dot-item-thumb">
                //     <img id="owl-dot-item-thumb" src="assets/images/products/zoom/product-2.jpg"/>
                // </div>
                // <div class="col-3 owl-dot" id="owl-dot-item-icon">
                //     <img id="owl-dot-item-icon" src="assets/images/products/zoom/product-3.jpg"/>
                // </div>`;
                // $('#carousel-custom-dots').trigger('replace.owl.carousel', productslides, { loop: true }).trigger('refresh.owl.carousel');
                $('.owl-carousel').trigger('replace.owl.carousel', productslides, {loop: true },{zoom: true }).trigger('refresh.owl.carousel');
                
                            // $('#main-markets').html('');

                            // response.data.forEach(element => {
                            //    console.table(element);
                       
                             
                            //         $('#main-markets').append(`
                            //                     <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                            //                         <div class="product-category">
                            //                             <a href="category.html">
                            //                                 <figure>
                            //                                     <img src="${element.media[0].url}" style="width:100%;height:200px">
                            //                                 </figure>
                            //                                 <div class="category-content">
                            //                                     <h3>${element.name}</h3>
                                                               
                            //                                 </div>
                            //                             </a>
                            //                         </div>
                            //                     </div>`);
        
                                
        
        
        
                            // })
        
                        }
        
                    })
                })();

                // alert(productID);
        // end product details 


        // start checkout 
            function goToCheckout(){
                    // console.log("xxxx");

                    var _token = localStorage.getItem("token");
                    // console.log("isLoggedin",_token);

                    if(_token==null){
                        window.location.href = 'login.html';
                    }
                    else{
                        window.location.href = 'checkout-shipping.html';
                        
                    }
                    

                }
        // end checkout 

        // start login
                function login(){

                    let fd= {
                        email:"a@b.com",
                        password:"123456"                
                    }
                //    console.group("creditional");
                //         console.log("email : ",$('#login-email').val() );
                //         console.log("password : ", $('#login-password').val() );
                //    console.groupEnd();

                   $.ajax({
                    url: baseURl+ "login",
                    method: 'POST',
                    // headers: header,
                    data:fd,
                    success: function (response) {
                        // $('#main-category').html('');
                        console.log("xo0ooo0o0o0o0oo");
                        console.table("api_token "+response.data.api_token);
                        localStorage.setItem("token",response.data.api_token);
                        window.location.href = 'checkout-shipping.html';
                        
                    }
                })


                }
        // end login


