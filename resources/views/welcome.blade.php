<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>

  <!-- BASICS -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>HospitalNote</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/isotope.css" media="screen" />
  <link rel="stylesheet" href="{{asset('green/js/fancybox/jquery.fancybox.css')}}" type="text/css" media="screen" />
  <link rel="stylesheet" href="{{asset('green/css/bootstrap.css')}}">
  <link rel="stylesheet" href="{{asset('green/css/bootstrap-theme.css')}}">
  <link href="{{asset('green/css/responsive-slider.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('green/css/animate.css')}}">
  <link rel="stylesheet" href="{{asset('green/css/style.css')}}">

  <link rel="stylesheet" href="{{asset('green/css/font-awesome.min.css')}}">
  <!-- skin -->
  <link rel="stylesheet" href="{{asset('green/skin/default.css')}}">
  <!-- =======================================================
    Theme Name: Green
    Theme URL: https://bootstrapmade.com/green-free-one-page-bootstrap-template/
    Author: BootstrapMade
    Author URL: https://bootstrapmade.com
  ======================================================= -->
</head>

<body>


  <div class="header">

      <div class="navbar navbar-fixed-top" role="navigation" data-0="line-height:100px; height:100px; background-color:rgba(0,0,0,0.3);" data-300="line-height:60px; height:60px; background-color:rgba(0,0,0,1);">

        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="fa fa-bars color-white"></span>
          </button>
          <h1><a class="navbar-brand" href="{{ url('/') }}" data-0="line-height:90px;" data-300="line-height:50px;">HOSPITALNOTE
            </a></h1>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav" data-0="margin-top:20px;" data-300="margin-top:5px;">
            <li class="active"><a href="#header">Home</a></li>
            <li><a href="#section-about">About</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#team">Team</a></li>
            <li><a href="#section-contact">Contact</a></li>

            @auth
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
            @else
                <li><a href="{{ route('login') }}">Login</a></li>
                  @if (Route::has('register'))
                      <li><a href="{{ route('register') }}">Register</a></li>
                  @endif
            @endauth

          </ul>
        </div>
        <!--/.navbar-collapse -->
      </div>


    </section>
  </div>

  <!--slider-->
  <div class="slider">
    <div id="about-slider">
      <div id="carousel-slider" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators visible-xs">
          <li data-target="#carousel-slider" data-slide-to="0" class="active"></li>
          <li data-target="#carousel-slider" data-slide-to="1"></li>
          <li data-target="#carousel-slider" data-slide-to="2"></li>
        </ol>

        <div class="carousel-inner">
          @if($homepages->count() > 0)
              @foreach ($homepages as $key => $homepage)

              <div class="item {{$key == 0 ? 'active' : ''}}" style="background-image: url({{asset('storage/'.$homepage->image)}});">
                <div class="carousel-caption">
                  <div class="wow fadeInUp" data-wow-offset="0" data-wow-delay="0.3s">
                    <h2>{{$homepage->title}}</h2>
                  </div>
                  <div class="col-md-10 col-md-offset-1">
                    <div class="wow fadeInUp" data-wow-offset="0" data-wow-delay="0.6s">
                      <p>{{$homepage->content}}</p>
                    </div>
                  </div>
                  <div class="wow fadeInUp" data-wow-offset="0" data-wow-delay="0.9s">
                    <form class="form-inline">
                      <div class="form-group">
                        <a class="btn btn-success btn-lg" href="{{route('register')}}" type="livedemo" name="Live Demo" >Live Demo</a>
                      </div>
                      <div class="form-group">
                        <a class="btn btn-default btn-lg" href="#section-contact" type="getnow" name="Get Now" >Get Now</a>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              
              @endforeach
          @else
              <strong class="text-center">No Sliders Set</strong>
          @endif          
        </div>

        <a class="left carousel-control hidden-xs" href="#carousel-slider" data-slide="prev">
          <i class="fa fa-angle-left"></i>
        </a>

        <a class=" right carousel-control hidden-xs" href="#carousel-slider" data-slide="next">
          <i class="fa fa-angle-right"></i>
        </a>
      </div>
      <!--/#carousel-slider-->
    </div>
    <!--/#about-slider-->
  </div>
  <!--/#slider-->

  <!--about-->
  <section id="section-about">
    <div class="container">
      <div class="about">
        <div class="row mar-bot40">
          <div class="col-md-offset-3 col-md-6">
            <div class="title">
              <div class="wow bounceIn">

                <h2 class="section-heading animated" data-animation="bounceInUp">Our Company</h2>


              </div>
            </div>
          </div>
        </div>
        <div class="row">

          @if($abouts->count() > 0)

            @foreach ($abouts as $about)
                <div class="row">
                  <div class="col-md-6">
                    @if ($about->image)
                            <img src="{{asset('storage/'.$about->image)}}" alt="{{$about->title}}" width="100%" height="350">
                        @else
                            <h5>Image not provided</h5>
                        @endif
                  </div>
                  <div class="col-md-6">
                    {!!$about->content!!}
                  </div>
                </div>
            @endforeach
              
          @else
            <strong>No About Us Content</strong>    
          @endif

        </div>

      </div>

    </div>
  </section>
  <!--/about-->


  <!-- Features -->
  <section id="features" class="section pad-bot5 bg-white">
    <div class="container">
      <div class="row mar-bot5">
        <div class="col-md-offset-3 col-md-6">
          <div class="section-header">
            <div class="wow bounceIn" data-animation-delay="7.8s">

              <h2 class="section-heading animated">Features</h2>
              <h4>The system offers the following features and more...</h4>

            </div>
          </div>
        </div>
      </div>
      <div class="row mar-bot40">
        @if($services->count() > 0)
            @foreach ($services as $service)
            <div class="col-lg-4">
              <div class="wow bounceIn">
                <div class="align-center">
    
                  <div class="wow rotateIn">
                    <div class="service-col">
                      <div class="service-icon">
                        <figure>
                          @if ($service->image)
                            <img src="{{asset('storage/'.$service->image)}}" alt="{{$service->title}}" width="100%">
                          @else
                              <h5>Image not provided</h5>
                          @endif
                        </figure>
                      </div>
                      <h2><a href="#">{{$service->title}}</a></h2>
                      <p>{!!$service->content!!}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
        @else
            <strong class="text-center">No Features</strong>
        @endif

      </div>

    </div>
  </section>
  <!--/features-->
  
<!-- team -->
<section id="team" class="team-section appear clearfix">
  <div class="container">

    <div class="row mar-bot10">
      <div class="col-md-offset-3 col-md-6">
        <div class="section-header">
          <div class="wow bounceIn">

            <h2 class="section-heading animated" data-animation="bounceInUp">Our Team</h2>
            <p>We have a team of dedicated professionals at your service.</p>

          </div>
        </div>
      </div>
    </div>

    <div class="row align-center mar-bot45">
      @if ($teams->count() > 0)
          @foreach ($teams as $team)
          <div class="col-md-4">
            <div class="wow bounceIn" data-animation-delay="4.8s">
              <div class="team-member">
                <div class="profile-picture">
                  <figure>
                        @if($team->image)
                            <img src="{{asset('storage/'.$team->image)}}" alt="{{$team->name}}" width="100%" height="400">
                        @else
                            <h5>Image not provided</h5>
                        @endif
                  </figure>
                  <div class="profile-overlay"></div>
                  <div class="profile-social">
                    <div class="icons-wrapper">
                      <a href="#"><i class="fa fa-facebook"></i></a>
                      <a href="#"><i class="fa fa-twitter"></i></a>
                      <a href="#"><i class="fa fa-linkedin"></i></a>
                      <a href="#"><i class="fa fa-pinterest"></i></a>
                      <a href="#"><i class="fa fa-google-plus"></i></a>
                    </div>
                  </div>
                </div>
                <div class="team-detail">
                  <h4>{{$team->name}}</h4>
                  <span>{{$team->role}}</span>
                </div>
                <div class="team-bio">
                  <p>{!! $team->bio !!}</p>
                </div>
              </div>
            </div>
          </div>
          @endforeach
      @else
          <strong class="text-center">No Team</strong>          
      @endif

    </div>

  </div>
</section>
<!-- /team -->

  <!-- contact -->
  <section id="section-contact" class="section appear clearfix">
    <div class="container">

      <div class="row mar-bot40">
        <div class="col-md-offset-3 col-md-6">
          <div class="section-header">
            <h2 class="section-heading animated" data-animation="bounceInUp">Contact us</h2>
            <p>Contact us today for any query or relevant information?</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div id="sendmessage">Your message has been sent. Thank you!</div>
          <div id="errormessage"></div>

        <form action="{{route('contact_query')}}" method="post" role="form" class="contactForm">

          @include('inc.messages')

          {{csrf_field()}}

            <div class="form-group">
              <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" data-rule="minlen:4"
                data-msg="Please enter at least 4 chars" />
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" data-rule="email"
                data-msg="Please enter a valid email" />
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4"
                data-msg="Please enter at least 8 chars of subject" />
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us"
                placeholder="Message"></textarea>
              <div class="validation"></div>
            </div>

            <div class="text-center"><button type="submit" class="line-btn green">Send Message</button></div>
          </form>

        </div>
        <!-- ./span12 -->
      </div>

    </div>
  </section>
  <!-- map -->
  <section id="section-map" class="clearfix">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d317715.7119145497!2d-0.38178209871231295!3d51.52873519925498!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8a00baf21de75%3A0x52963a5addd52a99!2sLondon%2C%20UK!5e0!3m2!1sen!2ske!4v1573633085426!5m2!1sen!2ske" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen></iframe>
  </section>

  <section id="footer" class="section footer">
    <div class="container">
      <div class="row animated opacity mar-bot0" data-andown="fadeIn" data-animation="animation">
        <div class="col-sm-12 align-center">
          <ul class="social-network social-circle">
            <li><a href="#" class="icoRss" title="Rss"><i class="fa fa-rss"></i></a></li>
            <li><a href="#" class="icoFacebook" title="Facebook"><i class="fa fa-facebook"></i></a></li>
            <li><a href="#" class="icoTwitter" title="Twitter"><i class="fa fa-twitter"></i></a></li>
            <li><a href="#" class="icoGoogle" title="Google +"><i class="fa fa-google-plus"></i></a></li>
            <li><a href="#" class="icoLinkedin" title="Linkedin"><i class="fa fa-linkedin"></i></a></li>
          </ul>
        </div>
      </div>

      <div class="row align-center copyright">
        <div class="col-sm-12">
          <p>&copy; HOSPITALNOTE  Copyright 2019</p>
          <div class="credits">
            <!--
              All the links in the footer should remain intact.
              You can delete the links only if you purchased the pro version.
              Licensing information: https://bootstrapmade.com/license/
              Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Green
            -->
          </div>
        </div>
      </div>
    </div>

  </section>
  <a href="#header" class="scrollup"><i class="fa fa-chevron-up"></i></a>

  <script src="{{asset('green/js/modernizr-2.6.2-respond-1.1.0.min.js')}}"></script>
  <script src="{{asset('green/js/jquery.js')}}"></script>
  <script src="{{asset('green/js/jquery.easing.1.3.js')}}"></script>
  <script src="{{asset('green/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('green/js/jquery.isotope.min.js')}}"></script>
  <script src="{{asset('green/js/jquery.nicescroll.min.js')}}"></script>
  <script src="{{asset('green/js/fancybox/jquery.fancybox.pack.js')}}"></script>
  <script src="{{asset('green/js/skrollr.min.js')}}"></script>
  <script src="{{asset('green/js/jquery.scrollTo.js')}}"></script>
  <script src="{{asset('green/js/jquery.localScroll.js')}}"></script>
  <script src="{{asset('green/js/stellar.js')}}"></script>
  <script src="{{asset('green/js/responsive-slider.js')}}"></script>
  <script src="{{asset('green/js/jquery.appear.js')}}"></script>
  <script src="{{asset('green/js/grid.js')}}"></script>
  <script src="{{asset('green/js/main.js')}}"></script>
  <script src="{{asset('green/js/wow.min.js')}}"></script>
  <script>
    wow = new WOW({}).init();
  </script>
  <script src="contactform/contactform.js"></script>


<!--Start of Tawk.to Script-->
<script type="text/javascript">
  var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
  (function(){
  var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
  s1.async=true;
  s1.src='https://embed.tawk.to/5e1093157e39ea1242a30615/default';
  s1.charset='UTF-8';
  s1.setAttribute('crossorigin','*');
  s0.parentNode.insertBefore(s1,s0);
  })();
  </script>
  <!--End of Tawk.to Script-->


  {{-- Cookies Permission --}}
  @include('cookieConsent::index')

</body>

</html>
