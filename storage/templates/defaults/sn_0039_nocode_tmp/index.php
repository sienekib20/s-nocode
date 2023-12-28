<style>
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


.faqs-item.active [class^="bi-"] {
  transition: 0.2s ease;
  transform: rotate(0deg);
}

.faqs-item.active [class^="bi-"] {
  transform: rotate(180deg);
}

.faqs-item.active .faqs-contain {
  height: auto;
  padding-bottom: 2rem;
}




/*
PRICING
*/

.card-pricing-header {
  position: relative;
  color: #fdfdfd;
  text-align: center;
}

.card-pricing-header .title {
  font-size: 30px;
  font-family: 'Cousine-Bold';
}

.card-pricing-header .ncode-btn-action.active {
  color: #fdfdfd;
  border-color: transparent;
  background-color: #06f;
}

.card-pricing-contain {
  display: none;
  align-items: start;
  position: relative;
  justify-content: center;
  gap: 10px;
  margin-top: 5rem;
}

.card-pricing-contain.active {
  display: flex;
}

.card-pricing-item {
  width: 280px;
  min-width: 280px;
  height: 420px;
  padding: 1rem 1.5rem;
  border-radius: 10px;
  position: relative;
  transition: 0.2s ease;
  background-color: #101021;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.card-pricing-item:hover {
  transform: translateY(-5px);
  border-color: rgba(255, 255, 255, 0.03);
}

.card-pricing-item:nth-child(2) {
  height: 450px;
}

.card-pricing-item .name {
  color: rgba(255, 255, 255, 0.7);
  display: block;
  font-size: 24px;
  line-height: 24px;
}

.card-pricing-item .price {
  display: block;
  margin: 1rem 0;
  color: #07f;
  font-size: 18px;
  font-family: 'Roboto-Medium';
}

.card-pricing-item .items {
  display: flex;
  flex-direction: column;
  margin: 2rem 0 0;
}

.card-pricing-item .items small {
  display: flex;
  align-items: center;
  gap: 10px;
  color: rgba(255, 255, 255, 0.6);
  font-family: 'Roboto-Light';
}

.card-pricing-item .items small+small {
  margin-top: 1rem;
}

.card-pricing-item .items+a {
  width: 80%;
  left: 10%;
  height: 35px;
  bottom: 5%;
  color: #fdfdfd;
  font-size: 13px;
  font-family: 'Roboto-Light';
  background-color: #06f;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
}


/*
IFRAME
*/

.card-section:has(.ncode-trailer) {
  height: 80vh;
}


.wall {
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  position: absolute;
  background: url('../images/ncode-2.jpg');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  opacity: 0.02;
}

.wall::after {
  content: '';
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  position: absolute;
  opacity: 0.1;
  background: linear-gradient(to left, #06f, #09f, #000, red);
}

.ncode-trailer {
  width: 100%;
  height: 60vh;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;

}

.ncode-trailer .bold {
  line-height: 24px;
  text-align: center;
  font-family: 'Cousine-Regular';
  margin-bottom: 2rem;
}

.ncode-video-play {
  display: flex;
  align-items: center;
  flex-direction: column;
  gap: 10px;
  z-index: 11111;
  color: #fdfdfd;
}

.ncode-video-play small[class^="bi-"] {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 35px;
  height: 35px;
  border-radius: 10px;
  aspect-ratio: 1/1;
  cursor: pointer;
  border: 1px solid rgba(255, 255, 255, 0.4);
}

iframe {
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: contain;
  position: absolute;
}




/*
TEMPLATE
*/

.card-templates-row {
  display: flex;
  align-items: start;
  flex-wrap: wrap;
  justify-content: space-between;
  margin: 4rem 0 0;
}

.card-templates-row:last-child {
  margin-bottom: 10rem;
}

.card-template-item {
  width: 240px;
  height: 225px;
  margin-top: 2rem;
  transition: 0.2s ease-in-out;
}

.card-template-item:hover {
  transform: translateY(-5px);
}

.card-template-item .cover {
  width: 100%;
  height: 160px;
  border-radius: 4px;
  position: relative;
}

.card-template-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  border-radius: 4px;
}

.card-template-item .cover::after {
  content: '';
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  position: absolute;
  opacity: 0.2;
  background: linear-gradient(to top, #07f, #000);
}

.card-template-item .cover .actions {
  top: 50%;
  left: 50%;
  position: absolute;
}

.template-info {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 0;
  font-family: 'Cousine-Regular';
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.template-info .name {
  display: block;
  color: #fdfdfd;
}

.template-info .name+small {
  font-size: 12px;
}






.sk-wrapper-not-found {
  width: 100%;
  height: 100vh;
  background-color: #f1f1f1;
  display: flex;
  align-items: center;
  justify-content: center;
}

.sk-not-found-text {
  color: rgba(0, 0, 0, 0.7);
}


.contain-has-filter .ncode-container {
  height: 100%;
  position: relative;
  display: flex;
  align-items: start;
  gap: 10px;
}

.has-filter {
  width: 20%;
}

.contain-templates {
  width: 100%;
}

.contain-templates img {
  width: 50%;
  height: 50%;
  object-fit: cover;
  object-position: center;
}



/*
toolbar
*/

.toolbar {
  width: 100%;
  height: 35px;
  display: flex;
  align-items: center;
  gap: 10px;
  /*overflow-y: auto;*/
  justify-content: center;
  /*box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);*/
  border-bottom: solid 1px rgba(0, 0, 0, 0.1);
}

.toolbar-items {
  gap: 10px;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  
  justify-content: center; 
  background-color: #F9FAFD; 
}

.toolbar::-webkit-scrollbar,
.toolbar-items::-webkit-scrollbar-track,
.toolbar-items::-webkit-scrollbar-thumb {
  width: 0px !important;
}

.toolbar-item {
  color: #000;
  font-size: 14px;
  min-width: 100px;
  width: 100px;
  height: 90%;
  min-height: 90%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: solid 1px transparent;
  cursor: pointer;
  white-space: nowrap;
}

.toolbar-item:hover {
  color: #fff;
  background-color: #cc0000;
}
/*
nav
*/

.ncode-navbar.white-bg {
  background-color: #fdfdfd !important;
}

.ncode-navbar.white-bg * {
  color: #000;
}

.auto {
  justify-content: end;
}

.nav-link small {
  font-size: 14px;
}

.initial {
  background-color: initial !important;
}

.__card-section-header {
  padding: 1rem 0;
}

.__card-section-header * {
  color: #000;
}

.__card-section-header .ncode-container {
  height: 100%;
  display: flex;
  align-items: center;
}

.__card-section-header .filters {
  margin-left: auto;
}

.tw-muted {
  color: #8a8a8a;
}

.__card-section-header .tw-muted + .bold {
  margin-top: -5px;
  font-size: 2vw;
  display: block;
  font-family: 'Roboto-Bold';
}

.__card-section-header button,
.__card-section-header select {
  outline: none;
  padding: 0.3rem 0.5rem;
  margin: 0;
  height: 30px;
  cursor: pointer;
  box-sizing: border-box;
  border: 1px solid rgba(0, 0, 0, 0.2);
  background-color: #fff;
  /*box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);*/
}

.__card-section-header button {
  color: #cc0000;
  margin-right: 10px; 
  border-color: #cc0000;
}

.__card-section-header button * { color: #cc0000; }
.__card-section-header button:hover { 
  color: #fff;
  background-color: #cc0000;
}

.__card-section-header button:hover * { color: #fff; }

/*
file template
*/
.linetemplate {
  width: 100%;
  display: flex;
  align-items: start;
  gap: 10px;
  flex-wrap: wrap;
  justify-content: space-between;
}

.linetemplate + .linetemplate {
  margin-top: 2rem;
}

.file-template {
  width: 320px;
  min-width: 300px;
  height: 260px;
  border-radius: 4px;
  display: flex;
  flex-direction: column;
  border: solid 1px rgba(0, 0, 0, 0.1);
}

.file-template .cover {
  width: 100%;
  height: 80%;
  position: relative;
  border-radius: 4px;
}

.file-template .cover img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 4px 4px 0 0;
}

.tempinfo,
.tempname {
  height: 10%;
  display: flex;
  align-items: center;
  padding: 0 1rem;
  justify-content: space-between;
}

.tempname {
  margin-bottom: -5px;
  font-family: 'Roboto-Bold';
}


.classificated {
  color: #8a8a8a;
  font-family: 'Roboto-Light';
  padding: 0.1rem 0.2rem;
  /*background-color: #0d0f11;*/
  margin-left: auto;
}

.abs_prev {
  left: 20%;
  top: 20%;
  z-index: 1111;
  width: 80px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #fff;
  position: absolute;
  opacity: 0;
  pointer-events: none;
  transition: 0.2s ease;
  transform: translate(-50%, -50%);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.file-template:hover .abs_prev {
  opacity: 1;
  pointer-events: all;
}


/*
page-manager
*/

.page-manager .ncode-container {
  display: flex;
  align-items: center;
  justify-content: center;
}

.page-manager .positions {
  width: 320px;
  height: 45px;
  display: flex;
  align-items: center;
  border: solid 1px #cc0000;
  border-radius: 4px;
  justify-content: space-between;
}

.page-manager .pos {
  width: 20%;
  height: 100%;
  display: flex;
  cursor: pointer;
  align-items: center;
  justify-content: center;
}

.page-manager .pos + .pos {
  border-left: solid 1px rgba(0, 0, 0, 0.1);
}

.page-manager .pos:hover,
.page-manager .pos.active {
  color: #fff;
  background-color: #cc0000;
  border-color: transparent;
}

.bg-white {
  background: #f4f4f4;
}

.nc-editor {
  position: relative;
}

.nc-editor-tools::-webkit-scrollbar {
  width: 10px;
}

.nc-editor-tools {
  width: 20%;
  height: 100%;
  position: fixed;
  overflow-y: auto;
  color: #8a8a8a;
  padding-left: 0.3rem;
  background-color: #191919;
  border: 1px solid rgba(0, 0, 0, 0.1);
  transition: 0.2s;
}

.nc-editor-tools.hide {
  width: 0;
  overflow: hidden;
}

.editor-tool-name {
  display: flex;
  align-items: center;
  height: 5%;
  color: #fdfdfd;
  font-size: 20px;
  gap: 10px;
  font-family: 'Roboto-Regular';
  padding: 0 1rem;
  display: none;
}

.editor-tool-name [class^="bi-"] {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 25px;
  height: 25px;
  border-radius: 2px;
  background-color: #cc0000;
}

.editor-tools-sections {
  /*margin-top: 1rem;*/
  padding: 1rem 0;
}

.editor-tools-sections + .editor-tools-sections {
  margin-top: 2rem;
}

.tools-section-header {
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 40px;
  font-size: 18px;
  padding: 0 0.5rem;
  color: #fdfdfd;
  border: solid 1px rgba(255, 255, 255, 0.4);
  border-width: 1px 0 1px 0;
  margin-bottom: 1rem;
}

.tools-section-header .title {
  display: flex;
  align-items: center;
  gap: 10px;

}

.tools-section-header [class^="bi-"]:not([class^="bi-chevron"]) {
  width: 25px;
  height: 25px;
  border-radius: 2px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #9d7703;
}

.tools-section-item {
  padding: 0 0.1rem;
  position: relative;
}

.tools-section-item+.tools-section-item {
  margin-top: 2rem;
}

.tools-section-item .contain:has(a) {
  display: flex;
  align-items: center;
  gap: 10px;
}

.tools-section-item .contain:has(a) a {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 35px;
  height: 35px;
  color: #f8f8f8;
  border-radius: 2px;
  background-color: rgba(255, 255, 255, 0.2);
}

.tools-section-item .title {
  display: block;
  margin: 0.5rem 0;
  height: 30px;
  line-height: 30px;
  padding: 0 0.5rem;
  background-color: rgba(255, 255, 255, 0.08);
}

.tools-section-item .iform:not(input[type="color"]) {
  width: 100%;
  height: 40px;
  color: #fdfdfd;
  padding: 0 0.5rem;
  outline: none;
  border-radius: 2px;
  background-color: transparent;
  border: solid 1px rgba(255, 255, 255, 0.2);
}

.tools-section-item label+small {
  width: auto !important;
  padding: 0 0.2rem;
}

.tools-section-item label+small,
.tools-section-item input+small {
  position: absolute;
  top: 75%;
  right: 2%;
  transform: translateY(-50%);
  width: 30px;
  height: 30%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.2);
}

input::placeholder {
  font-style: italic;
}

label {
  width: 100%;
  display: flex;
  align-items: center;
  cursor: pointer;
}

select {
  background-color: #191919 !important
}

.nc-editor-main-contain.expand {
  width: 100%;
  margin-left: 0;
}

.nc-editor-main-contain {
  width: 80%;
  margin-left: 20%;
  min-height: 100vh;
  transition: 0.2s ease;
}

.options-tools {
  display: flex;
  align-items: center;
  height: 50px;
  justify-content: space-between;
  color: #fdfdfd;
  padding: 0 0.5rem;
  background-color: #343A40;
}

.options-tools .actions {
  display: flex;
  gap: 10px;
}

.options-tools .actions a {
  padding: 0.3rem 0.5rem;
  border-radius: 2px;
  border: solid 1px rgba(255, 255, 255, 0.3);
  color: rgba(255, 255, 255, 0.6);
}

.options-tools .actions a:last-child {
  color: #fdfdfd;
  background-color: #cc0000;
}

.template-name {
  display: flex;
  align-items: center;
  gap: 10px;
}

.open-close-editor-tools {
  width: 33px;
  height: 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border-radius: 2px;
  cursor: pointer;
  border: solid 1px rgba(255, 255, 255, 0.5);
}

.open-close-editor-tools .line {
  display: block;
  width: 80%;
  height: 1px;
  pointer-events: none;
  background-color: rgba(255, 255, 255, 0.6);
}

.open-close-editor-tools .line + .line {
  margin-top: 0.3rem;
}

.responsive-view {
  display: flex;
  align-items: center;
  gap: 10px;
}

.responsive-view a {
  width: 35px;
  height: 35px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: rgba(255, 255, 255, 0.5);
  border: solid 1px rgba(255, 255, 255, 0.2);
  border-radius: 2px;
  font-size: 18px;
}

.responsive-view a.active {
  color: #fdfdfd;
  background-color: rgba(255, 255, 255, 0.1);
}


.containIframe {
  width: 100%;
  height: 100vh;
  position: relative;
}


iframe {
  top: 0;
  left: 0;
  width: 100%;
  min-height: 100%;
  position: absolute;
}

.mt-tiny {
  margin-top: 0.5rem;
}



</style><!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nocode</title>
</head>

<body>

  <nav class="ncode-navbar">
    <div class="ncode-container">
      <div class="ncode-app">
        <small class="bi-code-slash"></small>
        <small>nocode</small>
      </div>
      <div class="ncode-nav-items">
        <div class="nav-item">
          <a href="#" class="nav-link">
            <small>Inicio</small>
          </a>
        </div>
        <div class="nav-item">
          <a href="#" class="nav-link">
            <small>Explorar</small>
          </a>
        </div>
        <div class="nav-item">
          <a href="#" class="nav-link">
            <small>Planos</small>
          </a>
        </div>
        <div class="nav-item">
          <a href="#" class="nav-link">
            <small>Sobre nós</small>
          </a>
        </div>
        <div class="nav-item login">
          <a href="#" class="nav-link">
            <small>Entrar</small>
          </a>
        </div>
        <div class="nav-item">
          <a href="#" class="nav-link">
            <small>Criar conta</small>
          </a>
        </div>

        <div class="nav-item">
          <a href="#" class="nav-link">
            <small>Meus templates</small>
          </a>
        </div>
        <div class="nav-item">
          <a href="#" class="nav-link">
            <small class="bi bi-cart"></small>
          </a>
        </div>
      </div>
    </div>
  </nav>
  <header>
    <div class="wallpaper"></div>
    <div class="ncode-container">
      <div class="nocode-header-caption">
        <span class="bold">Sílica Nocode <br> nova tendência de negócios & <br> Torne-se independete
        </span>
        <small class="tw-muted">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolores dolor consequatur <br>
          ipsum debitis, quis blanditiis ab sint odit quidem labore.</small>

        <div class="ncode-actions">
          <a href="" class="ncode-btn-action active"> <small>Explorar</small></a>
          <a href="" class="ncode-btn-action"> <small>Registra-te</small></a>
        </div>

        <div class="titlebar">
          <div class="titlebar-item">
            <span class="title">+ 4.5K</span>
            <small>Usuários aderiram</small>
          </div>
          <div class="titlebar-item">
            <span class="title">+ 1.5K</span>
            <small>Landing Páginas</small>
          </div>
          <div class="titlebar-item">
            <span class="title">+ 0.5K</span>
            <small>Dashboard</small>
          </div>
        </div>

      </div>
    </div>
  </header>

  <div class="card-section">

    <div class="card-section-contain">
      <div class="ncode-container">
        <div class="card-section-row">
          <div class="contain-text">
            <div class="text-header">
              <span class="title">Nocode| Ferramenta Sílica</span>
            </div>
            <div class="text-items">
              <small class="bi-arrow-right">A Sílica a pensar sempre em ti trazendo soluções inovadoras</small>
              <small class="bi-arrow-right">Torne-se um parceiro com SP|Nocode</small>
              <small class="bi-arrow-right">Adera ao projeto e facilite o seu trablaho</small>
              <small class="bi-check tw-muted">Escolha um template a sua escolha</small>
              <small class="bi-check tw-muted">Edite-a com o seu tom de gosto e salve-a</small>
              <small class="bi-check tw-muted">Defina o novo domínio para o seu uso</small>
              <small class="bi-check tw-muted">E publique o seu novo site</small>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="card-section">
    <div class="wall"></div>
    <div class="card-section-contain">
      <div class="ncode-container">
        <div class="ncode-trailer">
          <span class="bold">Não sabes como Funciona? <br> Ensinamos você </span>
          <div class="ncode-video-play">
            <small class="bi-play-fill"></small>
            <small>Assistir</small>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="card-section">
    <div class="card-section-contain">
      <div class="ncode-container">
        <div class="card-pricing">
          <div class="card-pricing-header">
            <span class="title">ESCOLHA UM PLANO PARA SI</span>
            <div class="ncode-actions">
              <a href="" class="ncode-btn-action active" name="pricing-mensal"> <small>Mensal</small></a>
              <a href="" class="ncode-btn-action" name="pricing-anual"> <small>Anual</small></a>
            </div>
          </div>
          <div class="card-pricing-contain active" id="pricing-mensal">
            <div class="card-pricing-item">
              <span class="name"><small>Plano</small> <br> <span>Gratuito</span> </span>
              <span class="price">AO <span>0,00</span> </span>
              <div class="items">
                <small class="bi-check">1 Template no máximo</small>
                <small class="bi-check">Template sem interação</small>
                <small class="bi-check">Alteração limitada</small>
                <small class="bi-check">Domínio disponivel por 30 dias</small>
                <small class="bi-check">Sem suporte online</small>
              </div>
              <a href="" class="btn-action">Subscrever</a>
            </div>
            <div class="card-pricing-item">
              <span class="name"><small>Plano</small> <br> <span>Prata</span> </span>
              <span class="price">AO <span>37.500,00</span> </span>
              <div class="items">
                <small class="bi-check">7 Template no máximo</small>
                <small class="bi-check">Template interativo</small>
                <small class="bi-check">Alteração livre</small>
                <small class="bi-check">Domínio disponivel por 90 dias</small>
                <small class="bi-check">Suporte online</small>
              </div>
              <a href="" class="btn-action">Subscrever</a>
            </div>
            <div class="card-pricing-item">
              <span class="name"><small>Plano</small> <br> <span>Bronze</span> </span>
              <span class="price">AO <span>25.000,00</span> </span>
              <div class="items">
                <small class="bi-check">3 Template no máximo</small>
                <small class="bi-check">Template mais ou menos interativo</small>
                <small class="bi-check">Alteração normalizada</small>
                <small class="bi-check">Domínio disponivel por 45 dias</small>
                <small class="bi-check">Suporte online limitada</small>
              </div>
              <a href="" class="btn-action">Subscrever</a>
            </div>
          </div>

          <div class="card-pricing-contain" id="pricing-anual">
            <div class="card-pricing-item">
              <span class="name"><small>Plano</small> <br> <span>Gratuito</span> </span>
              <span class="price">AO <span>0,00</span> </span>
              <div class="items">
                <small class="bi-check">1 Template no máximo</small>
                <small class="bi-check">Template sem interação</small>
                <small class="bi-check">Alteração limitada</small>
                <small class="bi-check">Domínio disponivel por 60 dias</small>
                <small class="bi-check">Sem suporte online</small>
              </div>
              <a href="" class="btn-action">Subscrever</a>
            </div>
            <div class="card-pricing-item">
              <span class="name"><small>Plano</small> <br> <span>Prata</span> </span>
              <span class="price">AO <span>60.000,00</span> </span>
              <div class="items">
                <small class="bi-check">7 Template no máximo</small>
                <small class="bi-check">Template interativo</small>
                <small class="bi-check">Alteração livre</small>
                <small class="bi-check">Domínio disponivel por 180 dias</small>
                <small class="bi-check">Suporte online</small>
              </div>
              <a href="" class="btn-action">Subscrever</a>
            </div>
            <div class="card-pricing-item">
              <span class="name"><small>Plano</small> <br> <span>Bronze</span> </span>
              <span class="price">AO <span>45.000,00</span> </span>
              <div class="items">
                <small class="bi-check">3 Template no máximo</small>
                <small class="bi-check">Template mais ou menos interativo</small>
                <small class="bi-check">Alteração normalizada</small>
                <small class="bi-check">Domínio disponivel por 105 dias</small>
                <small class="bi-check">Suporte online limitada</small>
              </div>
              <a href="" class="btn-action">Subscrever</a>
            </div>
          </div>


        </div>

      </div>
    </div>
  </div>

  <?php
  $faqs = [
    [
      'title' => 'O que é preciso para começar?',
      'answer' => 'Primeiro tens que ter uma conta no Sílica, depois escolha um plano, só assim que podes começar'
    ],
    [
      'title' => 'Quando o meu plano atingir o limite, o meu site pára de funcionar?',
      'answer' => 'Antes que expire o teu plano receberas notificações antes do tempo'
    ],
    [
      'title' => 'É preciso saber programar pra modificar o template?',
      'answer' => 'Pensamos em si, podes não saber programar'
    ],
    [
      'title' => 'Com quem posso partilhar o meu dóminio',
      'answer' => 'Reposta a processar'
    ],
    [
      'title' => 'Existem template para o meu tipo de negócio?',
      'answer' => 'Os templates são de carater aberto para adequá-los ao teu tipo de negócio'
    ]
  ];
  ?>

  <div class="card-section">
    <div class="card-section-header">
      <div class="ncode-container">
        <div class="title">
          <span class="bold">Perguntas Mais Frequentes</span>
        </div>
      </div>
    </div>
    <div class="card-section-contain">
      <div class="ncode-container">
        <div class="card-faqs-container">
          <?php for ($i = 0; $i < count($faqs); $i++) : ?>
          <div class="faqs-item <?= ($i == 0) ? 'active' : '' ?>">
            <div class="faqs-header">
              <small class="name">
                <?= $faqs[$i]['title'] ?>
              </small>
              <small class="bi-chevron-down"></small>
            </div>
            <div class="faqs-contain">
              <small class="tw-muted">
                <?= $faqs[$i]['answer'] ?>
              </small>
            </div>
          </div>
          <?php endfor; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="card-section">
    <div class="card-section-header">
      <div class="ncode-container">
        <div class="title">
          <small class="tw-muted"></small>
          <span class="bold">Contactos</span>
        </div>
      </div>
    </div>
    <div class="card-section-contain">
      <div class="ncode-container">
        <div class="card-section-row">
          <div class="contacts">
            <div class="contact-item">
              <span class="text">TIre as tuas dúvidas</span>
            </div>
            <div class="contact-item">
              <small class="bi-pin-map-fill"></small>
              <div class="text">
                <small>Av. Doutor António A. Neto, Bairro Azul| Ingombota Luanda, Angola
                  Referencia Viaduto do Zamba 2 <br> Estamos aberto de segunda à sexta|das 8h às 17h</small>

              </div>
            </div>
            <div class="contact-item">
              <small class="bi-telephone-fill"></small>
              <div class="text">
                <small> <a href="">+244 948 109 778</a></small>
              </div>
            </div>
            <div class="contact-item">
              <small class="bi-envelope-at-fill"></small>
              <div class="text">
                <small> <a href="">cc@silicaweb.ao</a> </small>
              </div>
            </div>
            <div class="contact-item">
              <span class="text">Acompanhe-nos as redes sociais</span>
            </div>
            <div class="contact-social">
              <a href="https://webmail.silicaweb.ao/" class="btn-link" target="_blank"><small
                  class=""><small>Wm</small></small></a>
              <a href="" class="btn-link"><small class="bi-linkedin"></small></a>
              <a href="" class="btn-link"><small class="bi-facebook"></small></a>
              <a href="" class="btn-link"><small class="bi-youtube"></small></a>
            </div>
          </div>
          <form action="" class="subject">
            <div class="ncode-form-row">
              <div class="ncode-form-item">
                <input type="text" class="ncode-input" name="contact_name" required>
                <small class="placeholder">Nome</small>
              </div>
              <div class="ncode-form-item">
                <input type="text" class="ncode-input" name="contact_email" required>
                <small class="placeholder">Email</small>
              </div>
            </div>
            <div class="ncode-form-item">
              <input type="text" class="ncode-input" name="contact_subject" required>
              <small class="placeholder">Sujeito</small>
            </div>
            <div class="ncode-form-item">
              <textarea type="text" class="ncode-input" name="contact_msg" required></textarea>
              <small class="placeholder">Mensagem</small>
            </div>
            <div class="ncode-form-item">
              <button type="submit">Enviar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <footer>
    <div class="ncode-container">
      <small>&copy;
        <script>document.write(new Date().getFullYear());</script> Todo os direitos reservados | Patenteado por: <a
          href="https://silicaweb.ao/" class="color_silica" target="_blank">Sílica </a>
      </small>
    </div>
  </footer>

</body>

</html><script></script>