
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html {
  font-family: 'Segoe UI';
}

body {
  width: 100%;
  min-height: 100vh;
  position: relative;
  font-family: sans-serif;
  background-color: #151521;
  overflow-x: hidden;
}

.wrapper {
  display: flex;
  flex-direction: column;
  width: 100%;
  min-height: 100vh;
  position: relative;
}

.wrapper footer,
.wrapper .footer {
  margin-top: auto;
}

.white-bg {
  background-color: #fdfdfd !important;
}

a {
  color: #333;
  text-decoration: none;
}

img {
  max-width: 100%;
}

.main {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

footer {
  margin-top: auto;
}

.ncode-container {
  width: 80%;
  margin: 0 auto;
  position: relative;
}

.ncode-navbar {
  top: 0;
  left: 0;
  width: 100%;
  height: 60px;
  position: sticky;
  z-index: 111111111111;
  background-color: #151521;
}

.ncode-navbar .ncode-container {
  display: flex;
  height: 100%;
  align-items: center;
}

.ncode-app {
  color: #fdfdfd;
  font-family: 'Roboto-Bold';
  font-size: 20px;
}

.ncode-nav-items {
  display: flex;
  align-items: center;
  margin-left: 4rem;
  flex: 1;
}

.nav-item+.nav-item {
  margin-left: 2rem;
}

.nav-link {
  color: rgba(255, 255, 255, 0.4);
  position: relative;
}

.nav-item.active .nav-link,
.nav-item:hover .nav-link {
  color: #fdfdfd;
}

.nav-item.login {
  margin-left: auto;
}

.login a {
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 2px;
  padding: 0.4rem 1rem;
  color: #fdfdfd;
  border: 1px solid transparent;
  background-color: #07f;
}

.ncode-navbar.collapsed .ncode-nav-items {
  display: none;
}

.ncode-toggle {
  display: none;
}



/*
HEADER
*/

header {
  width: 100%;
  height: 90vh;
  position: relative;
}

header .bold {
  font-family: 'Roboto-Bold';
  font-size: 34px !important;
}

.mh {
  height: 60vh;
}

/*
header::after {
  content: '';
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  position: absolute;
  opacity: 0.2;
  background: linear-gradient(to right, rgba(0, 0, 0, 0.7), #180E32);
}*/

.wallpaper {
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  position: absolute;
  background: url('../images/back.png');
  background-size: cover;
  opacity: 0.2;
}

header form {
  margin: 2rem 0;
}

header form .bi-search {
  top: 50%;
  right: 5%;
  position: absolute;
  color: rgba(255, 255, 255, 0.6);
  transform: translateY(-50%);
}

.ncode-filter {
  margin-top: 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.ncode-filter-item {
  width: 120px;
  height: 35px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 5px;
  color: rgba(255, 255, 255, 0.4);
  font-family: 'Roboto-Light';
  cursor: pointer;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.ncode-filter-item.active {
  color: #06f;
  border-color: #06f;
}

.ncode-filter-item:not(.active):hover {
  color: #fdfdfd;
  border-color: transparent;
  background-color: #06f;
}


.nocode-header-caption {
  top: 20vh;
  width: 60%;
  margin: 0 auto;
  text-align: center;
  position: relative;
}


.bold {
  font-size: 20px;
  line-height: 34px;
  font-family: 'Cascadia';
  color: #fdfdfd;
  display: block;
  margin-bottom: 10px;
}

.tw-muted {
  color: #8A8A8A;
  display: block;
}



.ncode-actions {
  display: flex;
  margin: 2rem 0;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.titlebar-item,
.ncode-btn-action {
  width: 180px;
  height: 35px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: rgba(255, 255, 255, 0.4);
  padding: 0.2rem 0.3rem;
  border-radius: 4px;
  transition: 0.15s ease;
  border: solid 1px rgba(255, 255, 255, 0.4);
}

.ncode-btn-action.active,
.ncode-btn-action:hover {
  border-color: transparent;
  background-color: #06f;
  color: #fff;
}

.titlebar {
  margin-top: 8rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.titlebar-item {
  flex-direction: column;
  border-radius: 0;
  text-align: center;
  color: #fdfdfd;
  font-family: 'Roboto-Light';
  border-width: 0 1px 0 0;
}

.titlebar-item:last-child {
  border-width: 0;
}

.titlebar-item .title {
  color: #fdfdfd;
  font-size: 18px;
  font-family: 'Cascadia';
}


/*
cARDS
*/

.card-section,
.card-section-header,
.card-section-contain {
  padding: 1rem 0;
  width: 100%;
  position: relative;
}

.card-section-header {
  opacity: 0;
  transform: translateY(50px);
  transition: all 1.5s;
}

.card-section-header .bold {
  font-size: 24px;
  margin-bottom: 0px;
}

.card-section-header .tw-muted {
  font-size: 18px;
  line-height: 18px;
}


.card-section-contain {
  margin-top: 2rem;
}

.card-section-row {
  width: 100%;
  display: flex;
  align-items: start;
  justify-content: space-between;
}

.contain-static-slider,
.contain-text {
  width: 48%;
  position: relative;
  color: #fdfddf;
}

.text-header {
  color: #fdfdfd;
  font-family: 'Cousine-Bold';
  margin-bottom: 2rem;
  font-size: 20px;
}

.text-items small {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 1rem;
  font-family: 'Roboto-Light';
}

.contain-static-slider {
  display: flex;
  position: relative;
  gap: 2px;
}

.static-slider-item {
  width: 25%;
  height: 240px;
  position: relative;
  transform: skewY(2deg);
}

.static-slider-item:nth-child(2) {
  height: 280px;
  margin-top: -1rem;
}

.static-slider-item:nth-child(3) {
  height: 260px;
  margin-top: -2rem;
}

.static-slider-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}


.card-slider {
  display: flex;
  align-items: start;
  width: 100%;
  position: relative;
  overflow-x: auto;
  overflow: hidden;
}

.slider-item {
  width: 100%;
  min-width: 100%;
  max-width: 100%;
  height: 100%;
  position: relative;
}



/*
CONTACTS
*/

.card-section:has(.contacts) {
  background-color: #22222E;
  height: 80vh;
  padding-top: 4rem;
  margin-top: 2rem;
  border-radius: 0 0 0 10%;
}

.card-section-row:has(.contacts) {
  justify-content: space-between;
  margin-top: -2rem;
}

.contacts,
.subject {
  width: 46%;
  position: relative;
}

.contact-item+.contact-item {
  margin-top: 2rem;
}

.contact-item {
  display: flex;
  align-items: start;
  color: rgba(255, 255, 255, 0.7);
  color: rgba(255, 255, 255, 0.7);
  gap: 10px;
  font-family: 'Roboto-Light';
}

.contact-item a {
  color: rgba(255, 255, 255, 0.7);
}

.contact-item a:hover {
  text-decoration: underline;
}

.contact-social {
  margin-top: 1rem;
  display: flex;
  align-items: center;
  position: relative;
  gap: 10px;
}

.btn-link {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 30px;
  height: 30px;
  border-radius: 10%;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.btn-link small {
  color: rgba(255, 255, 255, 0.3);
}

.btn-link:hover small {
  color: rgba(255, 255, 255, 0.7);
}

/*
FOOTER
*/

footer {
  height: 60px;
  position: relative;
  /*background-color: #22222E;*/
}

footer .ncode-container {
  height: 100%;
  line-height: 60px;
  color: rgba(255, 255, 255, 0.7);
}


.color_silica {
  color: #07f;
}


/*
CONTACT FORM
*/

.ncode-form-item {
  width: 100%;
  height: 45px;
  border-radius: 4px;
  position: relative;
  border: solid 1px rgba(255, 255, 255, 0.1);
}

.ncode-form-item+.ncode-form-item {
  margin-top: 1rem;
}

.ncode-form-item button,
.ncode-input {
  width: 100%;
  height: 100%;
  background-color: transparent;
  outline: none;
  border: none;
  padding-left: 5%;
  color: #fdfdfd;
  font-family: 'Roboto-Light';
}

.ncode-input:focus+.placeholder,
.ncode-input:valid+.placeholder {
  top: 0;
  background-color: #22222E;
}

textarea {
  resize: none;
  padding-top: 6.5%;
}

.ncode-form-row {
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.ncode-form-row .ncode-form-item {
  width: 48%;
  margin: 0;
}

.ncode-form-row .placeholder {
  left: 10% !important;
}

.ncode-form-row .ncode-input {
  padding-left: 10% !important;
}

.ncode-form-item:has(textarea) {
  height: 80px;
}

.ncode-form-item .placeholder {
  top: 50%;
  left: 5%;
  position: absolute;
  color: rgba(255, 255, 255, 0.4);
  font-family: 'Roboto-Light';
  transform: translateY(-50%);
  transition: 0.2s ease-in-out;
}

.ncode-form-item button {
  background-color: #07f;
  cursor: pointer;
  border-radius: 4px;
}



/*
FAQS
*/

.card-faqs-container {
  width: 100%;
  display: flex;
  flex-direction: column;
}

.faqs-item {
  width: 100%;
  padding: 0 1rem;
  background-color: #101021;
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: #fdfdfd;
  margin-top: 1rem;
}

.faqs-header {
  width: 100%;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  cursor: pointer;
  font-size: 18px;
  font-family: 'Cousine-Regular';
}

.faqs-contain {
  height: 0;
  overflow: hidden;
  transition: 0.2s ease-in-out;
  font-family: 'Roboto-Light';
  padding: 0;
}


.faqs-item.active [class^=