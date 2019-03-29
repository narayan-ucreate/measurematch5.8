@php
$req_url=("$_SERVER[REQUEST_SCHEME]://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
@endphp
@if(Auth::check())
    <footer class="inner_footer">
        <ul>
            <li><a href="https://web.measurematch.com/terms-of-service" target="_blank" title="Terms of service">Terms of Service</a></li>
        <li><a href="https://web.measurematch.com/privacy-policy" target="_blank">Cookie Policy</a></li>
        <li><a  href="https://web.measurematch.com/code-of-conduct" target="_blank">Code of Conduct</a></li> 
        </ul>
        @php $year=date("Y"); @endphp
        <p>© {{$year}} MeasureMatch Ltd.</p>
    </footer>
@include('include.popup')
@else
@if(( $req_url != url('about-us')) &&  ( $req_url != url('our-experts')) && ( $req_url != url('publickservicepackage')) && ( $req_url != url('homepage/postproject')))
<section class="home-newsletter">
    <div class="container">
    <p>Subscribe to the MeasureMatch newsletter</p>
    <form id="subscription_newsletter" method="post" action="">
        {{ csrf_field() }}
        <input autocorrect="off" autocapitalize="none" type="text"  placeholder="Your email" name="newsletters" id="newsletters" value="">
        <input type="button" id="subscribe_newsletter" value="Subscribe" class="standard-btn" />
    <div id="email_error"></div>
    </form>
    </div>
</section>
@endif
<footer>
    <div class="container">
        <div class="col-lg-9 col-md-8 col-sm-6 col-xs-6 footer-logo-section">
            <a href="{{ url('/',[],$ssl) }}" class="footer-logo">
              <img src="{{ url('images/footer_logo.svg',[],$ssl)}}" />
            </a>
              <p>© {{date('Y')}} MeasureMatch Ltd.</p>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 footer-link-section text-right">
            <h5>MeasureMatch</h5>
              <ul>
                  <li><a href="{{ url('about-us',[],$ssl) }}" title="About us">About us</a></li>
                  <li><a target="_blank" href="{{ url('faq',[],$ssl) }}" title="FAQ">FAQ</a></li>
                  <li><a href="{{ url('contact-us',[],$ssl) }}" title="Contact Us">Contact us</a></li>
                  <li><a target="_blank" href="http://blog.measurematch.com" title="Blog">Blog</a></li>
                  <li><a target="_blank" href="{{ url('terms-of-service',[],$ssl) }}" title="Terms of service">Terms of service</a></li>
                  <li><a target="_blank" href="{{ url('privacy-policy',[],$ssl) }}" title="Privacy policy">Privacy policy</a></li>
                  <li><a target="_blank" href="{{ url('cookie-policy',[],$ssl) }}" title="Cookie policy">Cookie policy</a></li>
              </ul>
          </div>
          <div class="col-lg-1 col-md-2 col-sm-3 col-xs-3 footer-link-section">
            <h5>Connect</h5>
              <ul>
                <li><a href="https://www.linkedin.com/company/measurematch-ltd" target="_blank" title="LinkedIn">LinkedIn</a></li>
                  <li><a href="https://twitter.com/measurematch" target="_blank" title="Twitter">Twitter</a></li>
                  <li><a href="https://www.facebook.com/measurematchhq/" target="_blank" title="Facebook">Facebook</a></li>
				          <li><a href="mailto:contact@measurematch.com" title="Email">Email</a></li>
              </ul>
          </div>
      </div>
  </footer>
<script>  var base_url = "{{ url('/',[],$ssl) }}";</script>
<script src="{{ url('js/jquery.min.js?js='.$random_number,[],$ssl) }}"></script>
<script src="{{ url('js/home.js?js='.$random_number,[],$ssl) }}" type="text/javascript"></script>
@endif
