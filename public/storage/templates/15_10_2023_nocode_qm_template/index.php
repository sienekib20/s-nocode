<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI';
    }

    .loader {
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1111111111111111111111;
        position: fixed;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(0, 0, 0, 0.8);
        transition: 0.2s ease-in-out;
    }

    .loader.hide {
        display: none;
        opacity: 0;
        z-index: -1;
        pointer-events: none;
    }

    .loader small {
        display: block;
        width: 35px;
        height: 35px;
        aspect-ratio: 1/1;
        border-radius: 50%;
        border: solid 8px #cc0000;
        border-color: #cc0000 rgba(255, 255, 255, 0.2) rgba(255, 255, 255, 0.2) rgba(255, 255, 255, 0.2);
        animation: round 2s linear infinite;
    }

    @keyframes round {

        0%,
        100% {
            transform: rotate(360deg) scale(1.1);
        }

        50% {
            transform: rotate(0deg) scale(1.0);
        }
    }

    .container {
        width: 80%;
        margin: 0 auto;
    }

    .card-section,
    .card-section-header,
    .card-section-contain {
        padding: 1rem 0;
    }

    .card-section-header+.card-section-contain {
        margin-top: 4rem;
        margin-bottom: 1rem;
    }

    .card-section+.card-section {
        margin: 2rem 0 2rem;
    }

    .card-section-header {
        text-align: center;
    }

    .card-section-header .bold {
        font-size: 30px;
    }

    .card-section-header .bold+small {
        font-size: 20px;
    }

    img {
        object-fit: cover;
        object-position: center;
        max-width: 100%;
    }


    a {
        color: #000;
        text-decoration: none;
    }


    /*
navbar
 */
    .mobile-menu,
    .navbar {
        top: 0;
        left: 0;
        width: 100%;
        height: 80px;
        position: sticky;
        z-index: 11111;
        background-color: #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border-bottom: solid 6px #CC0000;
    }

    .mobile-menu {
        display: none;
    }

    .header-menu,
    .header-menu .container,
    .navbar .container {
        display: flex;
        align-items: center;
        height: 100%;
        justify-content: space-between;
    }

    .navbar .container {
        width: 100%;
        padding-left: 10%;
    }

    .nv-item.last {
        width: 240px;
        background-color: blue;
    }

    .nv-item.last .nv-link {
        width: 100% !important;
    }

    .mnv-items {
        top: 70px;
        right: 0%;
        width: 100%;
        height: 0px;
        position: absolute;
        display: flex;
        flex-direction: column;
        background-color: #fff;
        overflow-y: auto;
        transition: 0.2s;
        z-index: 111111111111111111;
        transition: 0.15s ease-in-out;
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.1);
    }

    .mnv-items.active {
        height: 310px;
    }

    .mnv-item {
        width: 100%;
        height: 50px;
    }

    .mnv-item.active a {
        color: #fff;
        background-color: #CC0000;
    }

    .mnv-link {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        padding-left: 2rem;
        font-size: 18px;
    }

    .mnv-link small {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .mnv-item.auto {
        margin-top: auto;
    }

    .nv-app {
        display: flex;
        align-items: center;
        font-size: 20px;
        color: #CC0000;
        position: relative;
    }

    .nv-app img {
        width: 160px;
        object-fit: cover;
        margin-top: -1rem;
    }

    .logo {
        display: flex;
        align-items: center;
        justify-content: center;
        aspect-ratio: 1/1;
        width: 28px;
        background-color: #CC0000;
        color: #fff;
        border-radius: 2px;
        font-weight: 500;
    }

    .bold {
        font-weight: bold;
        display: block;
    }

    .nv-items {
        height: 100%;
        display: flex;
        align-items: center;
    }

    .nv-item,
    .nv-link {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        font-size: 18px;
        position: relative;
    }

    .nv-item+.nv-item {
        margin-left: 2rem;
    }

    .nv-item>.dropdown {
        width: 340px;
        min-height: 100px;
        top: 105%;
        position: absolute;
        background-color: #fff;
        border-radius: 2px;
        opacity: 0;
        pointer-events: none;
        transition: 0.2s;
        border: solid 1px rgba(0, 0, 0, 0.05);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .nv-item:hover .dropdown {
        opacity: 1;
        pointer-events: all;
    }

    .dn-item,
    .dn-link {
        width: 100%;
        display: flex;
        align-items: center;
    }

    .dn-item {
        height: 45px;
    }

    .dn-item+.dn-item {
        margin-top: 0.5rem;
    }

    .dn-link {
        height: 100%;
        padding: 0.5rem 1rem;
        position: relative;
    }

    .nv-link::after,
    .dn-link::after {
        content: '';
        top: 50%;
        left: 0;
        width: 4px;
        height: 0;
        position: absolute;
        transform: translateY(-50%);
        transition: 0.2s ease-in-out;
        background-color: #CC0000;
    }

    .nv-link::after {
        top: 95% !important;
        left: 50%;
        width: 0 !important;
        height: 4px !important;
        transform: translateX(-50%);
    }


    .nv-link.active::after,
    .nv-link:hover::after {
        width: 0% !important;
    }

    .nv-link.active,
    .nv-link:hover,
    .dn-link:hover {
        color: #CC0000;
    }

    .dn-link:hover::after {
        height: 100%;
    }

    .dn-link:has([class^="bi-"]) small {
        display: flex;
        align-items: center;
        width: 100%;
        flex-direction: row-reverse;
        justify-content: space-between;
    }

    .nv-item:last-child .nv-link {
        width: 140px;
        color: #fff;
        background-color: #CC0000;
    }




    .bold {
        font-weight: bolder;
        font-family: 'Segoe UI Black';
        display: block;
    }

    .card-plan {
        display: flex;
        align-items: start;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: -1rem;
    }

    .card-plan-item {
        width: 320px;
        min-height: 435px;
        background-color: #fff;
        box-shadow: 0 0px 10px 2px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 2rem 2.5rem;
        overflow: hidden;
        position: relative;
        margin-top: 1rem;
    }

    .card-plan-item.big {
        height: 520px;
    }

    .card-plan-item.big::after {
        opacity: 0;
    }

    .card-plan-item::after {
        content: '';
        bottom: -50%;
        left: 0;
        width: 100%;
        height: 100%;
        position: absolute;
        transition: 0.2s;
        opacity: 0.2;
        background: linear-gradient(to bottom, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 1));
    }

    .card-plan-item:hover::after {
        opacity: 0;
        pointer-events: none;
    }

    .card-plan-header {
        text-align: left;
    }

    .card-plan-header .title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: -0.2rem;
    }

    .card-plan-header .title .bold {
        color: #CC0000;
    }

    .card-plan-header .title .brand {
        padding: 0.2rem 0.3rem;
        border-radius: 4px;
        border: solid 1px rgba(0, 0, 0, 0.2);
    }

    .card-plan-header .bold {
        font-size: 20px;
    }

    .price-reduction {
        margin: 1rem 0;
        display: flex;
        align-items: baseline;
        justify-content: center;
    }

    .tw-muted {
        color: #444;
    }

    .line-through {
        color: #8a8a8a;
        text-decoration: line-through;
    }

    .plan-colored {
        margin-left: 0.1rem;
        font-size: 14px;
        padding: 0.1rem 0.3rem;
        color: #fff;
        background-color: #CC0000;
    }

    .price {
        display: flex;
        align-items: baseline;
        justify-content: center;
        margin: 2rem 0 1rem;
        font-size: 20px;
    }

    .price-brand {
        display: flex;
    }

    .price .bold {
        font-size: 24px;
    }

    .plan-contain-price+.tw-muted {
        text-align: center;
        display: flex;
        margin-bottom: 0.5rem;
    }

    .action-btn {
        display: flex;
        height: 45px;
        border-radius: 3px;
        background-color: #CC0000;
        color: #fff;
        align-items: center;
        margin: 0.5rem 0;
        justify-content: center;
    }

    .pub {
        display: block;
        font-weight: bolder;
        font-family: 'Segoe UI Black';
        text-align: center;
        /*color: #A87C00;*/
    }

    .plan-contain-includes .title {
        font-weight: bolder;
        font-family: 'Segoe UI Black';
    }

    .all-includes small {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 0.5rem;
    }


    /*
|-----------------------------------------------
| barra de filtros
|-----------------------------------------------
|
|
*/
    .filter-bar {
        width: 45%;
        margin: 0 auto;
        background-color: transparent;
        height: 50px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        /*gap: 20px;*/
    }

    .filter-bar .fb-item {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 1px;
        background-color: #fdfdfd;
        height: 45px;
        cursor: pointer;
        padding: 0 1rem;
        border: solid 1px rgba(0, 0, 0, 0.05);
        box-shadow: 0 0px 7px rgba(0, 0, 0, 0.05);
    }

    .filter-bar .fb-item:hover {
        opacity: 0.5;
    }

    .filter-bar .fb-item.active {
        color: #fdfdfd;
        pointer-events: none;
        border-color: transparent;
        background-color: #CC0000;
    }

    /*
|-----------------------------------------------
| card-products
|-----------------------------------------------
|
|
*/
    .card-contain-products {
        width: 100%;
    }

    .card-products {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        align-items: start;
        margin-top: 2rem;
        transition: all 0.2s ease-in-out;
        justify-content: space-between;
    }

    .card-product-item {
        width: 30%;
        min-width: 30%;
        height: 240px;
        margin-top: 2rem;
        border-radius: 4px;
        position: relative;
        transition: all 0.2s 0.2s ease;
        border: solid 1px rgba(0, 0, 0, 0.15);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .card-product-item:hover {
        border-color: transparent;
        background-color: rgba(255, 255, 255, 0.5);
    }

    .card-product-item .cover {
        width: 100%;
        height: 80%;
        margin-top: 5%;
    }

    .card-product-item img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: 0.2s ease-in-out;
    }

    .card-product-item:hover img {
        transform: scale(1.5)
    }

    .product-onhover {
        left: 5%;
        bottom: 0;
        width: 50px;
        height: 100%;
        display: flex;
        gap: 10px;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: absolute;
        transition: 0.2s ease;
    }

    .card-product-item:hover .product-onhover {
        left: 5%;
    }

    .card-product-item:hover .product-name {
        opacity: 1;
    }

    .card-product-item:hover .product-add-btn {

        left: 0;
    }

    .product-add-btn {
        width: 40px;
        aspect-ratio: 1/1;
        display: flex;
        align-items: center;
        position: relative;
        left: -4rem;
        justify-content: center;
        transition: 0.2s ease;
        border: 1px solid rgba(0, 0, 0, 0.2);
    }

    .product-add-btn:hover {
        color: #fdfdfd;
        background-color: #CC0000;
    }


    .product-name {
        top: 5%;
        right: 4%;
        opacity: 0;
        transition: 0.2s ease;
        position: absolute;
        padding: 0.3rem 0.2rem;
        background-color: #CC0000;
        color: #fff;
    }

    .product-ratings {
        position: absolute;
        right: 5%;
        bottom: 4%;
        display: none;
        flex-direction: column;
    }

    .product-ratings.rated {
        display: flex;
    }


    /*
/*
SPLASH
*/

    .splash {
        margin-top: -1rem;
        height: 78vh;
        position: relative;
    }

    .wallpaper {
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        filter: sepia(10%) grayscale(10%);
        position: absolute;
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
        background-position: top center;
        opacity: 0;
        transition: opacity 1s linear;
    }

    .wallpaper.active {
        /*animation: fadeIn 2s linear;*/
        opacity: 1;
    }

    @keyframes ff {
        0% {
            /*opacity: 0;*/
        }

        100% {
            opacity: 1;
        }
    }

    .wallpaper:nth-child(1) {
        background-image: url('as/banner.jpg');
    }

    .wallpaper:nth-child(2) {
        background-image: url('as/fechado.jpg');
    }

    .wallpaper:nth-child(3) {
        background-image: url('as/family.jpg');
    }

    .wallpaper .overlay {
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0.9;
        position: absolute;
        filter: sepia(10%) grayscale(10%);
        background: linear-gradient(to left, rgba(0, 0, 0, 0.4), #000);
    }


    .captions {
        top: 40%;
        width: 100%;
        position: absolute;
        position: relative;
        z-index: 111;
        transform: translateY(-40%);
    }

    .captions .info {
        opacity: 0;
        display: none;
        transition: opacity 1s linear;
    }

    .captions .info.active {
        /*opacity: 1;**/
        display: block;
        animation: ff 1s linear forwards;
        animation-delay: 1s;
    }


    .captions .tiny {
        color: #fdfdfd !important;
    }

    .captions .bold {
        color: #fdfdfd;
        font-size: 20px;
        display: block;
        margin: 0.5rem 0;
    }


    .mv {
        display: flex;
        align-items: center;
        margin: 0 0 2rem;
        color: #fdfdfd;
        gap: 5px;
        font-size: 1.5rem;
        animation: mav 0.5s linear;
    }

    @keyframes mav {
        0% {
            margin-top: -3rem;
        }

        100% {
            margin-top: 0;
        }
    }

    .mv .name {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        border-radius: 2px;
        aspect-ratio: 1/1;
        background: #1E90FF;
    }

    .mv .article {}

    /*
FORM
 */


    footer {
        background-color: #f9f9f9;
    }

    footer .container {
        padding: 2rem 0;
    }

    footer .container:first-child {
        display: flex;
        align-items: start;
        flex-wrap: wrap;
    }

    .footer-item {
        width: 25%;
        min-width: 25%;
    }

    .footer-item .title {
        display: block;
        margin: 0 0 1rem;
        font-weight: bold;
        font-size: 16px;
    }

    .footer-item .all-includes {
        margin: 0 0 1rem;
    }

    .footer-item .all-includes small {
        margin-top: 1rem;
    }

    .footer-item .all-includes small:hover {
        cursor: pointer;
        opacity: 0.6;
    }

    .all-includes+.title {
        margin-top: 3rem;
    }

    small+form {
        margin-top: 2rem;
    }


    form,
    .footer-item {
        position: relative;
    }

    .footer-item input {
        outline: none;
        width: 100%;
        height: 50px;
        padding: 0.5rem 1rem;
        border: solid 1px rgba(0, 0, 0, 0.2);
    }

    .footer-item input:focus {
        border-color: #CC0000;
    }

    .tw-muted.c {
        margin: 1rem 0;
        display: block;
    }

    .contacts {
        margin: 1rem 0 0.5rem;
        display: flex;
        flex-direction: column;
    }

    .social {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 0.5rem;
    }

    .social a {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        border: solid 1px rgba(0, 0, 0, 0.4);
        width: 30px;
        aspect-ratio: 1/1;
    }

    .social a small {
        pointer-events: none;
    }

    .container+.container {
        margin-top: 1rem;
    }

    /*
TOOLBAR
 */

    .big {
        font-size: 20px;
    }

    .big+.tiny {
        font-size: 16px;
    }

    .toolbar .container {
        display: flex;
        align-items: start;
        justify-content: space-between;
        width: 100%;
    }

    .toolbar {
        justify-content: space-between !important;
    }

    .toolbar.a .container .container {
        display: flex;
        align-items: center;
        justify-content: space-between !important;
        width: 90%;
    }

    .toolbar .contain {
        justify-self: end;
    }

    .toolbar .contain {
        width: 45%;
    }

    .toolbar .all-includes small {
        font-size: 18px;
        margin-top: 2rem;
        gap: 20px;
    }

    .toolbar .all-includes.a small {
        font-size: 16px;
    }

    .toolbar .contain span {
        display: block;
        text-align: center;
        font-size: 240px;
        margin-bottom: -4rem;
    }

    .toolbar .contain span+small {
        text-align: center;
        display: block;
    }

    .toolbar .contain span+small a {
        text-decoration: underline;
    }



    .patrocineos {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 2rem;
    }

    .patro-item {
        width: 260px;
        height: 60px;
        display: flex;
        gap: 10px;
        align-items: start;
        border: solid 1px rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        background-color: #fff;
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 10px 2px rgba(0, 0, 0, 0.1), 0 2px -10px -2px rgba(0, 0, 0, 0.05);
    }

    .patro-item.c {
        height: 200px;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        border-color: transparent;
    }

    .patro-item.c img {
        /*width: 2rem;*/
        aspect-ratio: 1/1;
        object-fit: cover;
        border-radius: 50%;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    }

    .patro-item.c .anem {
        font-weight: bold;
        display: block;
        margin-bottom: 1rem !important;
    }

    .patro-item .contain .anem {
        display: block;
        margin-bottom: -0.3rem;
    }

    .patro-item>small {
        width: 28px;
        aspect-ratio: 1/1;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .tw-muted {
        display: block;
    }


    input,
    textarea,
    button {
        width: 100%;
        height: 45px;
        margin-top: 0.5rem;
        outline: none;
        font-family: 'Segoe UI';
        padding: 0.5rem 1rem;
        font-size: 15px;
        resize: none;
        border: solid 1px rgba(0, 0, 0, 0.2);
    }

    textarea {
        height: 75px;
    }

    input:focus,
    textarea:focus {
        border-color: #CC0000;
    }

    button {
        padding: 0;
        color: #fff;
        cursor: pointer;
        background-color: #CC0000;
    }

    .footer-item .enviar {
        width: 80px;
        height: 80%;
        top: 2%;
        right: -5%;
        position: absolute;
        border-color: transparent;
    }

















    /*
__MQ__
 */




    @media screen and (max-width: 1024px) {
        .navbar {
            display: none;
        }

        .mobile-menu {
            display: block;
        }

        .hamburger {
            font-size: 30px;
            cursor: pointer;
            width: 45px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-family: sans-serif;
            border: solid 1px #CC0000;
            color: #CC0000;
        }

        .hamburger small {
            pointer-events: none;
        }

        header .container {
            flex-direction: column;
        }

        header .container [class^="contain-"] {
            width: 80%;
        }

        .card-product-item {
            width: 45%;
            max-width: 45%;
            min-width: 45% ! !important;
        }
    }

    @media screen and (max-width: 992px) {
        .card-section-header {
            margin-bottom: -2rem;
        }

        .all-includes small {
            font-size: 16px !important;
        }

        .card-section-header .bold {
            font-size: 20px;
        }

        .bold {
            font-size: 26px !important;
        }

        .bold+.tiny {
            font-size: 22px !important;
        }

        .toolbar .container {
            flex-direction: column;
        }

        .toolbar .container .contain:nth-child(1) {
            margin-top: -4rem;
        }

        .toolbar .container .contain {
            width: 100%;
            margin-top: 2rem;
        }

        footer .container:first-child {
            flex-direction: column;
        }

        .footer-item {
            width: 100%;
        }

    }

    @media screen and (max-width: 768px) {
        header .container [class^="contain-"] {
            width: 100%;
        }

        .bold {
            font-size: 20px !important;
        }

        .bold+.tiny {
            font-size: 17px !important;
        }

        .info-includes small {
            font-size: 13px;
        }

        .contain-info a {
            width: 80%;
            height: 40px;

        }

        .choose-me,
        .domain-form {
            width: 100%;
        }

        .alternative .container {
            width: 100%;
        }

        .choose-me [class^="bi-chevron"] {
            display: none;
        }

        .list-item {
            flex-direction: column;
            align-items: start;
            min-height: 120px;
        }

        .list-item>small {
            font-size: 17px;
            margin-bottom: 1rem;
        }

        .list-item .actions {
            margin: 0;
            flex-direction: column;
            align-items: start;
        }

        .action-btn {
            height: 35px;
        }

        .card-product-item .product-onhover {
            left: 5%;
        }

        .card-product-item .product-name {
            opacity: 1;
        }

        .card-product-item .product-add-btn {

            left: 0;
        }
    }

    @media screen and (max-width: 678px) {
        .card-products {
            flex-direction: column;
        }

        .card-product-item {
            width: 75%;
            min-width: 75%;
            align-self: center;
        }
    }

    @media screen and (max-width: 527px) {
        .card-products {
            flex-direction: column;
        }

        .card-product-item {
            width: 90%;
            min-width: 90%;
            align-self: center;
        }
    }

    @media screen and (max-width: 360px) {
        .container {
            width: 90%;
        }

        header .container [class^="contain-"] {
            width: 100%;
        }

        .bold {
            font-size: 16px !important;
        }

        .bold+.tiny {
            font-size: 14px !important;
        }

        .info-includes small {
            font-size: 13px;
        }

        .all-includes small {
            font-size: 14px !important;
        }

        .contain-info a {
            width: 80%;
            height: 40px;
        }

        .card-product-item {
            width: 100%;
            min-width: 100%;
        }

    }
</style>
<div class="loader">
    <small></small>
</div>

<nav class="navbar">
    <div class="container">
        <div class="nv-app">
            <small class="logo">S</small>
            <small class="name">Market</small>
        </div>
        <div class="nv-items">
            <div class="nv-item">
                <a href="" class="nv-link active"> <small>Inicio</small></a>
            </div>
            <div class="nv-item">
                <a href="" class="nv-link"> <small>Planos de afiliação</small> </a>
            </div>

            <div class="nv-item">
                <a href="" class="nv-link"> <small>Todos artigos</small> </a>
            </div>
            <div class="nv-item">
                <a href="" class="nv-link"> <small class="bi-arrow-down">Sobre nós</small></a>
            </div>
            <div class="nv-item">
                <a href="" class="nv-link"> <small>Ajuda</small> </a>
            </div>
            <div class="nv-item last">
                <a href="" class="nv-link"> <small>Contactos</small> </a>
            </div>
        </div>
    </div>
</nav>

<div class="mobile-menu">
    <div class="header-menu">
        <div class="container">
            <div class="nv-app">
                <small class="logo">S</small>
                <small class="name">Market</small>
            </div>
            <div class="hamburger">
                <small class="bi-list">=</small>
            </div>
        </div>
    </div>
    <div class="overlay"></div>
    <div class="mnv-items">
        <div class="mnv-item active">
            <a href="" class="mnv-link">
                <small class="bi-house-fill">Inicio</small>
            </a>
        </div>
        <div class="mnv-item">
            <a href="" class="mnv-link">
                <small class="bi-layers-fill">Planos de afiliação</small>
            </a>
        </div>
        <div class="mnv-item">
            <a href="" class="mnv-link">
                <small class="bi-archive-fill">Todos Artigos</small>
            </a>
        </div>
        <div class="mnv-item">
            <a href="" class="mnv-link">
                <small class="bi-exclamation">Sobre nós</small>
            </a>
        </div>
        <div class="mnv-item">
            <a href="" class="mnv-link">
                <small class="bi-question-circle-fill">Ajuda</small>
            </a>
        </div>
        <div class="mnv-item">
            <a href="" class="mnv-link">
                <small class="bi-arrow-right">Contactos</small>
            </a>
        </div>
    </div>
</div>

<?php $img = [
    ['banner.jpg', ''], ['family.jpg', ''], ['fechado.jpg', '']
];
$textos = [
    [
        0 => 'Melhores artigos você encontra aqui na nossa loja,  <br> SMarket a bon preço',
        1 => 'Mais de 180 mil pessoas,adotaram a Mavenda para ajudar a aumentar a velocidade e <br> a qualidade do seu negócio, revolucionando seus métodos de inovação.',
        2 => 'active'
    ],
    [
        0 => 'Facilitamos as tuas compras e fechamos <br> o acordo de vendas deixando tudo pronto.',
        1 => 'Temos muitos clientes a fecharem o negócio connosco, esperamos <br> você aderir a SMarket para ajudar a aumentar a velocidade e a qualidade de suas compras.',
        2 => ''
    ],
    [
        0 => 'Muita agente estão a comprar na SMarket. Faça uma <br>  boa escolha, compre aqui',
        1 => 'Esperamos por ti, o que estás esperando, adira um dos nossos planos <br> e poupe 55%% dos teus gastos',
        2 => ''
    ],
];
?>
<div class="splash">
    <?php foreach ($img as $i) : ?>
        <div class="wallpaper mkabs">
            <div class="overlay"></div>
        </div>
    <?php endforeach; ?>

    <div class="captions">
        <div class="container">
            <?php foreach ($textos as $texto) : ?>
                <div class="info <?= $texto[2] ?>">
                    <span class="bold"><?= nl2br($texto[0]) ?></span>
                    <small class="tiny"><?= nl2br($texto[1]) ?></small>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="activators">
            <span class="bubble"></span>
        </div>
    </div>
    <div class="contain-clickable-slider"></div>
</div>

<div class="card-section">
    <div class="card-section-header">
        <div class="container">
            <div class="title">
                <div class="span bold">Todos artigos</div>
                <small class="tiny">Artigos de todos os pontos do Mundo</small>
            </div>
        </div>
    </div>

    <div class="card-section-contain">
        <div class="container">
            <?php
            $product_imgs = [
                [
                    'pngegg (26).png',
                    'casaco',
                    'Turco',
                    'rated'
                ],
                [
                    'pngegg (18).png',
                    'bolsa',
                    'Francêsa',
                    ''
                ],
                [
                    'pngegg (24).png',
                    'casaco',
                    'Brazileiro',
                    'rated'
                ],
                [
                    'pngegg (23).png',
                    'casaco',
                    'Turco',
                    ''
                ],
                [
                    'pngegg (22).png',
                    'casaco',
                    'Americano',
                    ''
                ],
                [
                    'pngegg (20).png',
                    'bolsa',
                    'Turco',
                    'rated'
                ]
            ];
            ?>
            <div class="card-contain-products">

                <div class="filter-bar">
                    <div class="fb-item active"> <small>Tudo</small> </div>
                    <div class="fb-item"> <small>Bolsas</small> </div>
                    <div class="fb-item"> <small>Casacos</small> </div>
                </div>

                <div class="card-products">
                    <?php foreach ($product_imgs as $product) : ?>
                        <div class="card-product-item" name="type-<?= $product[1] ?>">
                            <div class="cover">
                                <img src="as/<?= $product[0] ?>" alt="">
                            </div>
                            <div class="product-onhover">
                                <a href="#" class="product-add-btn">
                                    <small class="bi-cart">+</small>
                                </a>
                                <a href="#" class="product-add-btn">
                                    <small class="bi-link">♥</small>
                                </a>
                            </div>
                            <small class="product-name"><?= ucfirst($product[1]) . ' ' . $product[2] ?></small>
                            <div class="product-ratings <?= $product[3] ?>">
                                <small class="bi-star-fill"></small>
                                <small class="bi-star-fill"></small>
                                <small class="bi-star"></small>
                                <small class="bi-star"></small>
                                <small class="bi-star"></small>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-section">
    <div class="card-section-header">
        <div class="container">
            <div class="title">
                <span class="bold">É Bungle ?</span>
                <small class="tiny">Confiança acima de tudo</small>
            </div>
        </div>
    </div>
    <div class="card-section-contain">
        <div class="container">
            <div class="toolbar a">
                <div class="container">
                    <div class="container">
                        <div class="contain">
                            <div class="all-includes a">
                                <small class="bi-check">Uso de protoco SSL/H</small>
                                <small class="bi-check">Mecanismos elevados de seguranca</small>
                                <small class="bi-check">Ainda assim aumentamos</small>
                                <small class="bi-check">Provedores de acesso autenticados</small>
                                <small class="bi-check">Aspectos de seg melhorados</small>
                            </div>
                        </div>
                        <div class="contain">
                            <div class="all-includes a">
                                <small class="bi-check">Trabalhamos com algoritmos desejados</small>
                                <small class="bi-check">Fornecemos um papel melhor na sociedade</small>
                                <small class="bi-check">Fazemos isso e aqui</small>
                                <small class="bi-x line-through">Otimização de serviços</small>
                                <small class="bi-check">Busca rápida em search engines</small>
                            </div>
                        </div>
                        <div class="contain">
                            <div class="all-includes a">
                                <small class="bi-x line-through">Gestão de até 100 sites</small>
                                <small class="bi-check">Registro de domínio gratuitamente</small>
                                <small class="bi-x line-through">Configuração de endereço de email</small>
                                <small class="bi-check">Otimização de serviços</small>
                                <small class="bi-x line-through">Busca rápida em search engines</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-section">
    <div class="card-section-header">
        <div class="container">
            <div class="title">
                <span class="bold">Planos de afiliação</span>
                <small class="tiny">Torne-se um afiliado e beneficie mais recursos</small>
            </div>
        </div>
    </div>
    <div class="card-section-contain">
        <div class="container">

            <div class="card-plan">
                <?php for ($i = 0; $i < 2; $i++) : ?>
                    <div class="card-plan-item">
                        <div class="card-plan-header">
                            <div class="title">
                                <span class="bold">Premium</span>
                                <small class="brand">Bónus</small>
                            </div>
                            <small class="tw-muted">Plano prefeito para sites pessoais</small>
                        </div>
                        <div class="card-plan-contain">
                            <div class="plan-contain-price">
                                <div class="price">
                                    <span class="bold">5.000,00</span>
                                    <div class="price-brand">
                                        <small class="price-unit">AO</small>
                                        <small>/</small>
                                        <small class="decalage">mês</small>
                                    </div>
                                </div>
                                <div class="price-reduction">
                                    <small class="line-through">10.000,00 AO</small>
                                    <span class="plan-colored">menos 55%</span>
                                </div>
                            </div>
                            <small class="tw-muted">Lorem ipsum, dolor sit amet consectetur, adipisicing elit. Qui, nisi.</small>
                            <span class="pub" style="opacity: 0;">+2 mêses GRÁTIS</span>
                            <div class="plan-contain-action">
                                <a href="" class="action-btn">escolher plano</a>
                            </div>

                            <span class="separator"></span>

                            <div class="plan-contain-includes">
                                <small class="title">Este plano incluí</small>
                                <div class="all-includes">
                                    <small class="bi-check">→ Desempenho padrão</small>
                                    <small class="bi-check">→ 100 sites</small>
                                    <small class="bi-check">→ Backups diários</small>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>

<div class="card-section">
    <div class="card-section-header">
        <div class="container">
            <div class="title">
                <span class="bold">Quem somos ?</span>
                <small class="tiny" style="display: block; margin: 1rem 0 0 !important;"> <q><i> Somos a SMarket, um aglomerador de Lojas para faciltar as tuas compras, <br> bem como os teus negócios de vendas</i></q></small>
            </div>
        </div>
    </div>
    <div class="card-section-contain">
        <div class="container">
            <span class="bold big"></span>
            <div class="patrocineos">
                <a href="" class="patro-item">
                    <small class="bi-google"></small>
                    <div class="contain">
                        <small class="anem">Mavenda</small>
                        <small class="tw-muted">CEO e instructor</small>
                    </div>
                </a>
                <a href="" class="patro-item">
                    <small class="bi-apple"></small>
                    <div class="contain">
                        <small class="anem">Alberto</small>
                        <small class="tw-muted">Desenvolvedor mobile</small>
                    </div>
                </a>
                <a href="" class="patro-item">
                    <small class="bi-android"></small>
                    <div class="contain">
                        <small class="anem">Joel</small>
                        <small class="tw-muted">Conceptor & Designer</small>
                    </div>
                </a>
                <a href="" class="patro-item">
                    <small class="bi-amd"></small>
                    <div class="contain">
                        <small class="anem">Barroso</small>
                        <small class="tw-muted">Relações interiores</small>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card-section">
    <div class="card-section-header">
        <div class="container">
            <div class="title">
                <span class="bold">Check out</span>
            </div>
        </div>
    </div>
    <div class="card-section-contain">
        <div class="container">

        </div>
    </div>
</div>


<div class="card-section">
    <div class="card-section-header"></div>
    <div class="card-section-contain">
        <div class="container">
            <div class="toolbar a">
                <div class="container">
                    <div class="container">
                        <div class="contain">
                            <small class="bold big">Conheça o nosso Call Center</small>
                            <small class="tiny">Ligue a qualquer momento</small>
                            <?php for ($x = 0; $x < 8; $x++) : ?>
                                <div class="card-section-header"></div>
                            <?php endfor; ?>
                        </div>
                        <div class="contain">
                            <form action="" method="">
                                <input type="text" name="nome" placeholder="Nome & Apelido" required autocomplete="off">
                                <input type="text" name="email" placeholder="Email" required autocomplete="off">
                                <input type="text" name="sujeito" placeholder="Sujeito" required autocomplete="off">
                                <textarea name="mensagem" placeholder="Mensagem" cols="30" rows="10" required autocomplete="off"></textarea>
                                <button>Submeter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-section"></div>


<footer>
    <div class="container">
        <div class="footer-item">
            <div class="name">
                <div class="nv-app">
                    <small class="logo">S</small>
                    <small class="name">Market</small>
                </div>
                <small class="tw-muted">A compra na palma da tua mão</small>
                <div></div>
                <small class="tw-muted">Puramente angolana</small>
                <div></div>
            </div>
        </div>

        <div class="footer-item">
            <small class="title">Serviços</small>
            <div class="all-includes">
                <small class="bi-chevron-right">Formação profisionalizante</small>
                <small class="bi-chevron-right">Programação de contéudos</small>
                <small class="bi-chevron-right">Publicidade & Marketing</small>
                <small class="bi-chevron-right">Mentoria de projetos</small>
                <small class="bi-chevron-right">Gestão de projetos</small>
                <small class="bi-chevron-right">Gestão empresarial</small>
                <small class="bi-chevron-right">Iniciação ao empreendedorismo</small>
            </div>
            <small class="title">Outros</small>
            <div class="all-includes">
                <small class="bi-chevron-right">Design gráfico</small>
                <small class="bi-chevron-right">Design UI/UX</small>
                <small class="bi-chevron-right">Desgin Patterns</small>
                <small class="bi-chevron-right">Base de layouts</small>
                <small class="bi-chevron-right">Comunicação interna</small>
                <small class="bi-chevron-right">E muito mais</small>
            </div>
        </div>

        <div class="footer-item">
            <small class="title">Patrocinadores</small>
            <div class="all-includes">
                <small class="bi-chevron-right">GM Empreendimentos</small>
                <small class="bi-chevron-right">Pacote Sílica</small>
                <small class="bi-chevron-right">Kianda Labs</small>
                <small class="bi-chevron-right">BJNK Soluções Tecnológico LDA</small>
            </div>
            <small class="title">Localização</small>
            <div class="all-includes">
                <small class="bi-arrow-right">Rua São Miguel de Santos, Nº12</small>
                <small class="bi-pin-map">Ref: Portão branco (Os combatentes). Luanda|Angola</small>
                <small class="bi-headset">Call center: +244 9XX XX XXX</small>
                <small class="bi-reception-2">Fax: +244 222 23 124</small>
            </div>
        </div>

        <div class="footer-item">
            <small class="title">Subscreva-te</small>
            <div class="all-includes">
                <small>Para receber mais informações nossas <br> E te manter sempre actualizado</small>
                <form action="">
                    <input type="text" placeholder="email">
                    <button class="enviar">Enviar</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <small>&copy;<script>
                document.write(new Date().getFullYear())
            </script> Todos os direitor reservados.</small>
        <div>
            <small>Powered by: <a href="">GM Empreendimentos</a> | Desgin feito por: <a href="https://www.facebook.com/profile.php?id=100091417419139" target="_blank">Sienekib Kelyson</a> </small>
        </div>
    </div>
</footer>


<script>
    //const overlay = document.querySelector('.overlay');
    const navitems = document.querySelector('.mnv-items');
    document.querySelector('.hamburger').addEventListener('click', (e) => {
        //overlay.classList.add('active');
        navitems.classList.toggle('active');
    });

    // Para as imagens .wallpaper
    const wallpapers = document.querySelectorAll('.wallpaper');
    const count = wallpapers.length;
    let index = 0;

    function fadeInWallpapers() {
        wallpapers.forEach(wallpaper => {
            //wallpaper.classList.remove('active');
            wallpaper.className = "wallpaper mkabs";
        });

        wallpapers[index].className = "wallpaper active mkabs";
        //wallpapers[index].classList.add('active');
        index++;

        if (index === count) {
            index = 0;
        }
    }

    function startFadeInWallpapers() {
        fadeInWallpapers();
        setInterval(() => {
            fadeInWallpapers();
        }, 5000);
    }

    startFadeInWallpapers();

    // Para as informações .info
    const info = document.querySelectorAll('.info');
    const counter = info.length;
    let ____i = 0;

    function fadeInInfo() {
        info.forEach(infoElement => {
            infoElement.classList.remove('active');
        });

        info[____i].classList.add('active');
        ____i++;

        if (____i === counter) {
            ____i = 0;
        }
    }

    function startFadeInInfo() {
        fadeInInfo();
        setInterval(() => {
            fadeInInfo();
        }, 5000);
    }

    startFadeInInfo();

    const loader = document.querySelector('.loader');
    loader.style.display = 'none';
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
        loader.classList.add('hide');
        document.body.style.overflow = 'auto';
    }, 2000);
    window.addEventListener('load', (e) => {

    });
</script>
