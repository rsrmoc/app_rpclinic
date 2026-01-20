@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Notícias</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('motivo.listar') }}">Principais notícias da Momento</a></li>
            </ol>
        </div>
    </div>

    <style>
        @keyframes live-blink {
            0% {
                opacity: 1
            }

            50% {
                opacity: 0.5
            }

            100% {
                opacity: 1
            }
        }

        .back-btn__text,
        .search-filter-mobile-component .advanced-filters__dropdown__text,
        .search-filter-mobile-component .advanced-filters__dropdown__advanced-date-filter,
        .search-filter-mobile-component .advanced-filters__dropdown__advanced-date-filter__selected {
            font-size: 15px
        }

        @media (max-width: 480px) {

            .back-btn__text,
            .search-filter-mobile-component .advanced-filters__dropdown__text,
            .search-filter-mobile-component .advanced-filters__dropdown__advanced-date-filter,
            .search-filter-mobile-component .advanced-filters__dropdown__advanced-date-filter__selected {
                font-size: 22px
            }
        }

        body#serp-body {
            background: #fcfcfc;
            box-sizing: border-box
        }

        .lock-scroll {
            height: 50vh;
            overflow: hidden
        }

        .lock-scroll #regua-navegacao {
            display: none
        }

        #content {
            transition: opacity 0.3s ease-in-out
        }

        #content.has-pub {
            display: flex;
            flex-direction: row-reverse;
            justify-content: space-between
        }

        @media (max-width: 480px) {
            #content {
                background-color: #f2f2f2;
                padding-bottom: 20px;
                padding-top: 20px;
                padding-left: 0;
                padding-right: 0
            }
        }

        #content.loading {
            opacity: 0.3
        }

        .container {
            max-width: 1360px;
            padding-right: 20px;
            padding-left: 20px;
            margin-right: auto;
            margin-left: auto
        }

        @media (min-width: 1200px) {
            .container {
                padding-right: 85px;
                padding-left: 85px
            }
        }

        .container:after,
        .row:after {
            content: "";
            display: table;
            clear: both
        }



        .back-btn {
            display: flex;
            justify-content: flex-start
        }

        .back-btn__text {
            font-family: OpenSans-Light, Arial, "Helvetica Neue", Helvetica, sans-serif;
            cursor: pointer;
            user-select: none;
            text-transform: none;
            color: #666
        }

        .back-btn__icon {
            border: solid #a4a4a4;
            border-width: 0 2px 2px 0;
            padding: 3px;
            height: 7px;
            align-self: center;
            transform: rotate(135deg);
            margin-right: 15px
        }

        #header-produto {
            overflow: hidden;
            background-color: #f3f3f3
        }

        @media (max-width: 480px) {
            #header-produto {
                padding-bottom: 1px
            }

            #header-produto .barra-globocom {
                display: none
            }
        }

        @media (min-width: 481px) {
            #header-produto {
                height: 118px
            }
        }

        .has-regua .search-header__menu-button-separator {
            display: none
        }

        .search-header {
            position: absolute;
            width: 100%;
            overflow: hidden
        }

        @media (min-width: 481px) {
            .search-header {
                top: 44px
            }
        }

        @media (max-width: 480px) {
            .search-header .container {
                padding: 0
            }
        }

        @media (max-width: 480px) {
            .search-header {
                position: relative
            }
        }

        @media (min-width: 481px) {
            .search-header {
                height: 74px
            }
        }

        .search-header__menu-area {
            display: block;
            float: left;
            overflow: visible;
            white-space: nowrap
        }

        @media (max-width: 480px) {
            .search-header__menu-area {
                display: none
            }
        }

        .search-header__menu-button-separator {
            display: inline-block;
            float: left;
            transition: width 0.3s ease-in-out, opacity 0.3s ease-in-out
        }

        .search-header__menu-button-separator__safari {
            display: none
        }

        @media (min-width: 941px) {
            .search-header__menu-button-separator__safari {
                display: inline-block
            }
        }

        .search-header__menu-button-separator__safari-mobile {
            display: none
        }

        .search-header__menu-button {
            display: inline-block;
            vertical-align: top;
            padding: 27px 0
        }

        .search-header__menu-button .burger {
            cursor: pointer;
            display: inline-block;
            vertical-align: middle;
            margin-bottom: 2px
        }

        .search-header__menu-button .burger b {
            width: 23px;
            height: 2px;
            display: block;
            background: #fff;
            margin-bottom: 4px
        }

        .search-header__menu-button .burger b:last-child {
            margin-bottom: 0
        }

        .search-header__menu-label {
            display: inline-block;
            font-size: 14px;
            line-height: 20px;
            color: #fff;
            margin-left: 3px;
            font-family: ProximaNova-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif
        }

        .search-header__separator {
            background: url("//s.glbimg.com/es/ge/static/live/header_produto/img/dotted.png") repeat-y top left;
            height: 20px;
            margin-top: 27px;
            margin-left: 20px;
            padding-left: 20px;
            display: inline-block;
            vertical-align: top
        }

        .search-header .logo {
            margin-top: 27px;
            margin-right: 20px;
            color: #fff;
            line-height: 20px;
            height: 20px;
            display: inline-block
        }

        .search-header .logo .logo-produto {
            height: 20px;
            outline: 0;
            border: 0;
            -ms-interpolation-mode: bicubic;
            image-rendering: optimizeQuality
        }

        .search-header .logo .logo-desktop {
            display: inline
        }

        .search-header .logo .logo-mobile {
            display: none
        }

        .search-header .logo .logo-retina-desktop {
            display: none
        }

        .search-header .logo .logo-retina-mobile {
            display: none
        }

        @media (min-width: 481px) {
            .search-header--menu-opened .search-header__menu-button-separator {
                overflow: hidden;
                width: 0px !important;
                opacity: 0
            }
        }

        .search-header__menu-area-smart {
            display: none
        }

        @media (max-width: 480px) {
            .search-header__menu-area-smart {
                display: block
            }
        }

        .burger-smart {
            top: 50%;
            margin-top: -1px
        }

        .burger-smart,
        .burger-smart::after,
        .burger-smart::before {
            display: inline-block;
            width: 20px;
            height: 2px;
            border-radius: 0;
            background: #fff;
            left: 0;
            position: absolute
        }

        .burger-smart:before {
            top: -6px;
            content: ''
        }

        .burger-smart:after {
            bottom: -6px;
            content: ''
        }

        .menu-button-smart {
            width: 20px;
            box-sizing: border-box;
            height: 50px;
            text-align: left;
            display: inline-block;
            vertical-align: middle
        }

        .menu-area-smart {
            top: 0;
            left: .75rem;
            font-size: 0;
            box-sizing: border-box;
            display: inline-block;
            position: absolute;
            font-size: 0
        }

        .main-container {
            display: block;
            width: 100%;
            padding-right: .75rem;
            padding-left: .75rem;
            text-align: center;
            line-height: 48px
        }

        .left-container {
            display: inline-block;
            position: absolute;
            left: 0
        }

        .right-container {
            display: inline-block;
            right: 0;
            vertical-align: middle;
            position: absolute;
            padding-right: .75rem
        }

        .center-container {
            height: 50px;
            width: 200px;
            display: inline-block
        }

        .search-icon {
            vertical-align: middle;
            display: inline-block
        }

        .product-logo {
            vertical-align: middle;
            display: inline-block
        }

        .footer {
            color: #fff;
            padding: 10px 0;
            font-size: 11px
        }

        @media (max-width: 480px) {
            .footer {
                display: none
            }
        }

        .footer a {
            color: #fff
        }

        .footer__options {
            float: right
        }

        .footer__options li {
            float: left;
            padding: 0 6px
        }

        .search-form {
            position: relative;
            overflow: hidden
        }

        .search-form__cancel-button__smart {
            display: none
        }

        .search-form__search-icon {
            display: none
        }

        @media (max-width: 480px) {
            .search-form__search-field {
                width: auto;
                height: 50px;
                padding-top: 0;
                padding-right: 15.5px;
                padding-left: .75rem;
                overflow: hidden;
                background-color: #fff;
                border: 0;
                border-radius: 0
            }

            .search-form__search-field__input {
                float: left;
                display: inline-block;
                width: calc(100% - 103px);
                height: 50px;
                font-size: 24px;
                line-height: 50px;
                vertical-align: middle;
                font-family: OpenSans-Light, Arial, "Helvetica Neue", Helvetica, sans-serif;
                letter-spacing: -1px;
                color: #333;
                outline: 0
            }

            .search-form__search-field__input-filled {
                width: calc(100% - 161px)
            }

            .search-form__search-field__input::placeholder {
                text-transform: lowercase
            }

            .search-form__search-field__autocomplete {
                display: none
            }

            .search-form__icon {
                display: none
            }

            .search-form__clean-button {
                width: 24px;
                height: 24px;
                margin: 13px 0px 13px 21px;
                background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjQsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zOnNrZXRjaD0iaHR0cDovL3d3dy5ib2hlbWlhbmNvZGluZy5jb20vc2tldGNoL25zIg0KCSB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCINCgkgdmlld0JveD0iLTIgLTIgMjQgMjQiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgLTIgLTIgMjQgMjQiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPHJlY3QgaWQ9IkJHIiB4PSItMiIgeT0iLTIiIGZpbGw9IiNGRkZGRkYiIGZpbGwtb3BhY2l0eT0iMCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0Ii8+DQo8ZWxsaXBzZSBpZD0iT3ZhbC0xNDMiIGZpbGw9IiM5OTk5OTkiIGN4PSIxMCIgY3k9IjEwIiByeD0iMTAiIHJ5PSIxMCIvPg0KPHBhdGggaWQ9IlJlY3RhbmdsZS03MC1Db3B5LTIiIGZpbGw9IiNGRkZGRkYiIGQ9Ik0xMCw5LjI1M0w2Ljc0Nyw2TDYsNi43NDdMOS4yNTMsMTBMNiwxMy4yNTRMNi43NDcsMTRMMTAsMTAuNzQ2TDEzLjI1NCwxNA0KCUwxNCwxMy4yNTRMMTAuNzQ2LDEwTDE0LDYuNzQ3TDEzLjI1NCw2TDEwLDkuMjUzTDEwLDkuMjUzeiIvPg0KPC9zdmc+DQo=") no-repeat scroll 0 0 #fff;
                display: inline-block
            }

            .search-form__submit-button {
                display: none
            }

            .search-form__cancel-button__smart {
                float: right;
                background: #fff;
                font-family: ProximaNova, Arial, "Helvetica Neue", Helvetica, sans-serif;
                color: #666;
                outline: 0;
                font-size: 11px;
                height: 24px;
                margin: 13px 0px;
                padding-right: 0;
                padding-left: .75rem;
                display: inline-block;
                border-left: 1px solid #efefef
            }

            .search-form__search-icon {
                margin: 15px 6px 0px 0px;
                display: inline-block;
                float: left;
                width: 24px;
                height: 24px;
                color: #666;
                text-align: center
            }
        }

        @media (min-width: 481px) {
            .search-form {
                padding-top: 19px
            }

            .search-form__search-field {
                position: relative;
                width: auto;
                height: 35px;
                overflow: hidden;
                background-color: #fff;
                border-radius: 3px
            }

            .search-form__search-field__icon {
                display: inline-block;
                margin: 10px 4px 10px 0;
                float: left;
                width: 27px;
                height: 15px;
                background: url(../header_padrao/img/sprites_busca_v14.png) no-repeat scroll 8px -896px #fff
            }

            .search-form__search-field__input,
            .search-form__search-field__autocomplete {
                position: absolute;
                display: block;
                width: 100%;
                padding-left: 30px;
                padding-right: 43px;
                z-index: 2;
                height: 35px;
                min-width: 92px;
                color: #000;
                font-family: OpenSans, Arial, "Helvetica Neue", Helvetica, sans-serif;
                font-size: 14px;
                font-weight: bold;
                letter-spacing: -0.5px;
                line-height: normal;
                border: 0;
                background: rgba(255, 255, 255, 0);
                -webkit-appearance: none;
                -moz-appearance: none;
                -webkit-user-modify: read-write-plaintext-only
            }

            .search-form__search-field__input:focus,
            .search-form__search-field__autocomplete:focus {
                outline: none
            }

            .search-form__search-field__autocomplete {
                position: absolute;
                z-index: 1;
                color: silver
            }

            .search-form__clean-button {
                position: relative;
                z-index: 5;
                display: none;
                float: right;
                width: 20px;
                height: 20px;
                margin-top: 8px;
                margin-right: 5px;
                cursor: pointer;
                background: url(../header_padrao/img/sprites_busca_v14.png) no-repeat scroll 2px -875px #fff
            }
        }

        .range-date-filter-modal {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 40;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .range-date-filter-modal__header {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            padding: 6px 23px 23px 23px
        }

        .range-date-filter-modal__header__title {
            font-family: ProximaNova-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 16px
        }

        .range-date-filter-modal__header__close {
            cursor: pointer
        }

        .range-date-filter-modal__container {
            padding: 17px 0;
            background-color: #ffffff;
            box-shadow: -1px 4px 6px 0 rgba(0, 0, 0, 0.1), 1px -1px 6px 0 rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            text-transform: capitalize;
            font-family: OpenSans, Arial, "Helvetica Neue", Helvetica, sans-serif
        }

        .range-date-filter-modal__btn-section {
            display: flex;
            padding: 10px 23px 0px 23px
        }

        .range-date-filters__dates-section {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            padding: 2px 30px 0px 30px;
            margin-top: 10px;
            min-width: 250px
        }

        .range-date-filters__dates-section__text {
            color: #bbb
        }

        .range-date-filters__dates-section__date {
            padding: 4px;
            border: 1px solid #bbb;
            border-radius: 3px
        }

        @media (max-width: 940px) {
            .search-filter-component {
                display: none
            }
        }

        .search-filter-component .filter-container {
            box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.1);
            justify-content: center;
            margin-bottom: 30px
        }

        .search-filter-component .filter-desktop {
            max-width: 1360px;
            padding-right: 20px;
            padding-left: 20px;
            margin-right: auto;
            margin-left: auto;
            position: relative;
            flex: 1
        }

        @media (min-width: 1200px) {
            .search-filter-component .filter-desktop {
                padding-right: 85px;
                padding-left: 85px
            }
        }

        .search-filter-component .filters {
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.3px;
            font-family: ProximaNova-Regular, Arial, "Helvetica Neue", Helvetica, sans-serif;
            color: #393939;
            display: flex;
            justify-content: space-between
        }

        .search-filter-component .filters__container {
            height: 51px;
            display: flex
        }

        .search-filter-component .filters__item {
            align-self: center
        }

        .search-filter-component .filters__selected-label {
            font-family: ProximaNova-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif
        }

        .search-filter-component .filters__advanced-date-filter {
            height: 51px;
            display: flex
        }

        .search-filter-component .filters__clear-button {
            margin-left: 12px;
            text-transform: capitalize;
            user-select: none;
            cursor: pointer;
            color: #666
        }

        .search-filter-component .filters__dropdown {
            margin-left: 3px;
            margin-right: 9px
        }

        .search-filter-component .filters__dropdown__link {
            padding: 16px 0 16px 0
        }

        .search-filter-component .filters__dropdown__icon {
            border: solid #a4a4a4;
            border-width: 0 1px 1px 0;
            display: inline-block;
            padding: 2px;
            transform: rotate(45deg);
            margin: 0px 0px 3px 5px
        }

        .search-filter-component .filters__dropdown__list {
            padding: 17px 0;
            display: none;
            position: absolute;
            z-index: 6;
            background-color: #ffffff;
            box-shadow: -1px 4px 6px 0 rgba(0, 0, 0, 0.1), 1px -1px 6px 0 rgba(0, 0, 0, 0.1);
            margin-top: 16px;
            border-radius: 4px;
            text-transform: capitalize;
            font-family: OpenSans, Arial, "Helvetica Neue", Helvetica, sans-serif
        }

        .search-filter-component .filters__dropdown__list:before {
            position: absolute;
            display: inline-block;
            padding: 8px;
            transform: rotate(45deg);
            margin: -23px 0px 0px 12px;
            background: #fff;
            box-shadow: -3px -3px 4px -2px rgba(0, 0, 0, 0.1);
            z-index: 2;
            content: ' '
        }

        .search-filter-component .filters__dropdown__list__header {
            padding: 9px 26px;
            font-family: ProximaNova-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif;
            text-transform: uppercase;
            color: #bbb
        }

        .search-filter-component .filters__dropdown__list__item,
        .search-filter-component .filters__dropdown__list__range-date__text {
            padding: 9px 26px;
            cursor: pointer;
            color: #666;
            display: block;
            text-transform: lowercase;
            margin: 0
        }

        .search-filter-component .filters__dropdown__list__item::first-letter,
        .search-filter-component .filters__dropdown__list__range-date__text::first-letter {
            text-transform: uppercase
        }

        .search-filter-component .filters__dropdown__list__item__selected,
        .search-filter-component .filters__dropdown__list__range-date__text__selected {
            padding: 9px 26px;
            cursor: pointer;
            color: #666;
            display: block;
            text-transform: lowercase;
            margin: 0;
            font-weight: bold
        }

        .search-filter-component .filters__dropdown__list__item__selected::first-letter,
        .search-filter-component .filters__dropdown__list__range-date__text__selected::first-letter {
            text-transform: uppercase
        }

        .search-filter-component .filters__dropdown__list__range-date {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            margin-top: 15px;
            padding-top: 15px
        }

        .search-filter-component .filters__dropdown__list__range-date__icon {
            border: solid #a4a4a4;
            border-width: 0 1px 1px 0;
            display: inline-block;
            padding: 3px;
            transform: rotate(315deg);
            margin: 0px 0px 1px 25px
        }

        .search-filter-component .filters__dropdown__list__right {
            padding: 17px 0;
            display: none;
            position: absolute;
            z-index: 6;
            background-color: #ffffff;
            box-shadow: -1px 4px 6px 0 rgba(0, 0, 0, 0.1), 1px -1px 6px 0 rgba(0, 0, 0, 0.1);
            margin-top: 16px;
            border-radius: 4px;
            text-transform: capitalize;
            font-family: OpenSans, Arial, "Helvetica Neue", Helvetica, sans-serif;
            right: 20px
        }

        @media (min-width: 1200px) {
            .search-filter-component .filters__dropdown__list__right {
                right: 85px
            }
        }

        .search-filter-component .filters__dropdown__list__right:before {
            position: absolute;
            display: inline-block;
            padding: 8px;
            transform: rotate(45deg);
            right: 12px;
            top: -8px;
            background: #fff;
            box-shadow: -3px -3px 4px -2px rgba(0, 0, 0, 0.1);
            z-index: 2;
            content: ' '
        }

        .search-filter-component .filters__dropdown:hover>.filters__dropdown__list__right {
            display: block
        }

        .search-filter-component .filters__dropdown:hover>.filters__dropdown__list {
            display: block
        }

        .search-filter-mobile-component {
            display: none;
            line-height: 1
        }

        @media (max-width: 940px) {
            .search-filter-mobile-component {
                display: block
            }
        }

        .search-filter-mobile-component .advanced-filters {
            display: none;
            position: absolute;
            top: 159px;
            left: 0;
            width: 100%;
            height: calc(100% - 230px);
            background-color: #fff;
            overflow: auto;
            -webkit-overflow-scrolling: touch;
            z-index: 15;
            text-transform: uppercase;
            font-size: 12px;
            font-family: ProximaNova-Regular, Arial, "Helvetica Neue", Helvetica, sans-serif;
            color: #393939
        }

        @media (max-width: 480px) {
            .search-filter-mobile-component .advanced-filters {
                top: 88px;
                height: calc(100% - 88px)
            }

            .search-filter-mobile-component .advanced-filters__show-services-header__active {
                top: 140px;
                height: calc(100% - 140px)
            }
        }

        .search-filter-mobile-component .advanced-filters__btn-section {
            display: flex;
            padding: 30px 20px 0px 20px
        }

        .search-filter-mobile-component .advanced-filters__actions {
            display: none;
            padding: 20px;
            position: absolute;
            z-index: 30;
            bottom: 0;
            left: 0;
            width: 100%;
            pointer-events: none;
            background-image: linear-gradient(to bottom, rgba(255, 255, 255, 0), #fff 30px)
        }

        .search-filter-mobile-component .advanced-filters__actions__apply-btn {
            width: 100%;
            border-radius: 3px;
            box-shadow: 0 1px 0 0 rgba(0, 0, 0, 0.2);
            border: solid 1px #ebebeb;
            background: #fff;
            display: block;
            text-transform: uppercase;
            font-family: ProximaNova-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 13px;
            line-height: 45px;
            text-align: center;
            pointer-events: all
        }

        .search-filter-mobile-component .advanced-filters__range-date-filter {
            display: flex;
            flex-direction: column;
            align-items: center
        }

        .search-filter-mobile-component .advanced-filters__dropdown__text {
            cursor: pointer;
            color: #666;
            display: block;
            text-transform: lowercase
        }

        .search-filter-mobile-component .advanced-filters__dropdown__text::first-letter {
            text-transform: uppercase
        }

        .search-filter-mobile-component .advanced-filters__dropdown__list-item {
            font-family: OpenSans-Light, Arial, "Helvetica Neue", Helvetica, sans-serif;
            margin: 20px;
            user-select: none
        }

        .search-filter-mobile-component .advanced-filters__dropdown__list-item__selected {
            font-family: OpenSans-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-weight: bold
        }

        .search-filter-mobile-component .advanced-filters__dropdown__list-item__selected {
            font-family: OpenSans-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif;
            margin: 20px;
            font-weight: bold;
            user-select: none
        }

        .search-filter-mobile-component .advanced-filters__dropdown__header {
            margin: 20px;
            padding-top: 15px;
            font-family: ProximaNova-Regular, Arial, "Helvetica Neue", Helvetica, sans-serif;
            text-transform: uppercase;
            color: #bbb
        }

        .search-filter-mobile-component .advanced-filters__dropdown__advanced-date-filter {
            margin: 20px;
            font-family: OpenSans-Light, Arial, "Helvetica Neue", Helvetica, sans-serif;
            cursor: pointer;
            user-select: none;
            text-transform: none;
            margin: 25px 0px 25px 0px;
            color: #666
        }

        .search-filter-mobile-component .advanced-filters__dropdown__advanced-date-filter__selected {
            margin: 20px;
            font-family: OpenSans-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif;
            cursor: pointer;
            user-select: none;
            text-transform: none;
            margin: 25px 0px 25px 0px;
            color: #666;
            font-weight: bold;
            user-select: none
        }

        .search-filter-mobile-component .advanced-filters__dropdown__icon {
            border: solid #a4a4a4;
            border-width: 0 2px 2px 0;
            padding: 3px;
            height: 7px;
            align-self: center;
            transform: rotate(-45deg);
            margin: 5px
        }

        .search-filter-mobile-component .advanced-filters__dropdown__date-filter {
            display: none;
            justify-content: space-between;
            border-top: 1px solid #00000015;
            margin: 35px 20px 100px 20px
        }

        @media (max-width: 480px) {
            .search-filter-mobile-component .advanced-filters__dropdown__date-filter {
                display: flex
            }
        }

        .search-filter-mobile-component .advanced-filters__dropdown__date-filter-list {
            display: flex
        }

        @media (max-width: 480px) {
            .search-filter-mobile-component .advanced-filters__dropdown__date-filter-list {
                display: none
            }
        }

        .search-filter-mobile-component .filters {
            box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.1);
            background-color: #fff;
            position: relative;
            z-index: 20;
            padding: 15px .75rem;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0;
            font-family: ProximaNova-Regular, Arial, "Helvetica Neue", Helvetica, sans-serif;
            color: #393939;
            margin-bottom: 30px
        }

        @media (max-width: 480px) {
            .search-filter-mobile-component .filters {
                margin-bottom: 0px
            }
        }

        @media (max-width: 340px) {
            .search-filter-mobile-component .filters {
                font-size: 11px
            }
        }

        .search-filter-mobile-component .filters__button-label {
            float: right
        }

        .search-filter-mobile-component .filters__selection-label {
            font-size: 11px
        }

        .search-filter-mobile-component .filters__selected-filter__selected-label {
            font-family: ProximaNova-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif
        }

        .search-filter-mobile-component .filters__selected-filter__advanced-filters-btn {
            border: solid #a4a4a4;
            border-width: 0 2px 2px 0;
            display: inline-block;
            float: right;
            padding: 3px;
            transform: rotate(45deg);
            margin: 0px 5px 0px 0px
        }

        .search-filter-mobile-component .filters--opened .advanced-filters {
            display: flex
        }

        @media (max-width: 480px) {
            .search-filter-mobile-component .filters--opened .advanced-filters {
                display: block
            }
        }

        .search-filter-mobile-component .filters--opened .advanced-filters__actions {
            display: block
        }

        .search-filter-mobile-component .filters--opened .filters__selected-filter__advanced-filters-btn {
            transform: rotate(225deg);
            margin: 5px 5px 0px 0px
        }

        .results {
            overflow: hidden;
            min-height: 380px;
            clear: both
        }

        @media (min-width: 941px) {
            .results__content {
                max-width: 100%
            }
        }

        .results__list {
            clear: both
        }

        .results--videos .results__content {
            max-width: 100%
        }

        .suggestions-list {
            position: absolute;
            z-index: 60;
            width: 100%;
            font-family: OpenSans, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 18px;
            background: #fff;
            box-shadow: 0 4px 5px rgba(0, 0, 0, 0.3);
            padding: 6px 0
        }

        .suggestions-list li {
            color: #000;
            display: block;
            width: 100%;
            padding-left: 5px;
            line-height: 40px;
            cursor: pointer
        }

        .suggestions-list li.selected,
        .suggestions-list li:hover {
            background-color: #eee
        }

        .suggestions-list li:before {
            display: inline-block;
            width: 15px;
            height: 15px;
            margin-left: 20px;
            margin-right: 20px;
            vertical-align: middle;
            background: url(../header_padrao/img/sprites_busca_v14.png) no-repeat scroll -3px -896px transparent;
            content: ' '
        }

        .pagination {
            display: none;
            clear: both;
            text-align: center;
            margin-bottom: 50px
        }

        .pagination a {
            border-radius: 3px;
            display: block;
            color: #fff;
            text-transform: uppercase;
            font-family: ProximaNovaAlt-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-weight: 700;
            font-size: 14px;
            line-height: 45px;
            text-align: center;
            box-shadow: inset 0 -2px 0 0 rgba(0, 0, 0, 0.2);
            transition: opacity 0.3s ease-in-out
        }

        .pagination a.loading {
            opacity: 0.3
        }

        .PresetDateRangePicker_panel {
            padding: 0 22px 11px
        }

        .PresetDateRangePicker_button {
            position: relative;
            height: 100%;
            text-align: center;
            background: 0 0;
            padding: 4px 12px;
            margin-right: 8px;
            font: inherit;
            font-weight: 700;
            line-height: normal;
            overflow: visible;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            cursor: pointer
        }

        .PresetDateRangePicker_button:active {
            outline: 0
        }

        .PresetDateRangePicker_button__selected {
            color: #fff
        }

        .CalendarDay {
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            cursor: pointer;
            font-size: 14px;
            text-align: center
        }

        .CalendarDay:active {
            outline: 0
        }

        .CalendarDay__defaultCursor {
            cursor: default
        }

        .CalendarDay__default {
            color: #484848;
            background: #fff;
            line-height: 40px
        }

        .CalendarDay__default:hover {
            background: #e4e7e7;
            color: inherit;
            border-radius: 100%
        }

        .CalendarDay__hovered_offset {
            background: #f4f5f5;
            color: inherit
        }

        .CalendarDay__outside {
            border: 0;
            background: #fff;
            color: #484848
        }

        .CalendarDay__outside:hover {
            border: 0
        }

        .CalendarDay__blocked_minimum_nights {
            background: #fff;
            color: #eee;
            border-radius: 100%
        }

        .CalendarDay__blocked_minimum_nights:active,
        .CalendarDay__blocked_minimum_nights:hover {
            background: #fff;
            color: #eee;
            border-radius: 100%
        }

        .CalendarDay__highlighted_calendar {
            background: #ffe8bc;
            color: #484848
        }

        .CalendarDay__highlighted_calendar:active,
        .CalendarDay__highlighted_calendar:hover {
            background: #ffce71;
            color: #484848
        }

        .CalendarDay__last_in_range,
        .CalendarDay__last_in_range:hover {
            border-style: solid
        }

        .CalendarDay__selected,
        .CalendarDay__selected:active,
        .CalendarDay__selected:hover {
            border-radius: 100%;
            color: #fff
        }

        .CalendarDay__blocked_calendar,
        .CalendarDay__blocked_calendar:active,
        .CalendarDay__blocked_calendar:hover {
            background: #fff;
            color: #D1D0D1;
            border-radius: 100%
        }

        .CalendarDay__blocked_out_of_range,
        .CalendarDay__blocked_out_of_range:active,
        .CalendarDay__blocked_out_of_range:hover {
            background: #fff;
            color: #eee;
            border-radius: 100%
        }

        .CalendarMonth {
            background: #fff;
            text-align: center;
            vertical-align: top;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none
        }

        .CalendarMonth_table {
            border-collapse: collapse;
            border-spacing: 0
        }

        .CalendarMonth_verticalSpacing {
            border-collapse: separate
        }

        .CalendarMonth_caption {
            color: #484848;
            font-size: 18px;
            text-align: center;
            padding-top: 22px;
            padding-bottom: 37px;
            caption-side: initial
        }

        .CalendarMonth_caption__verticalScrollable {
            padding-top: 12px;
            padding-bottom: 7px
        }

        .CalendarMonthGrid {
            background: #fff;
            text-align: left;
            z-index: 0
        }

        .CalendarMonthGrid__animating {
            z-index: 1
        }

        .CalendarMonthGrid__horizontal {
            position: absolute;
            left: 9px
        }

        .CalendarMonthGrid__vertical {
            margin: 0 auto
        }

        .CalendarMonthGrid__vertical_scrollable {
            margin: 0 auto;
            overflow-y: scroll
        }

        .CalendarMonthGrid_month__horizontal {
            display: inline-block;
            vertical-align: top;
            min-height: 100%
        }

        .CalendarMonthGrid_month__hideForAnimation {
            position: absolute;
            z-index: -1;
            opacity: 0;
            pointer-events: none
        }

        .CalendarMonthGrid_month__hidden {
            visibility: hidden
        }

        .DayPickerNavigation {
            position: relative;
            z-index: 2
        }

        .DayPickerNavigation__horizontal {
            height: 0
        }

        .DayPickerNavigation__verticalDefault {
            position: absolute;
            width: 100%;
            height: 52px;
            bottom: 0;
            left: 0
        }

        .DayPickerNavigation__verticalScrollableDefault {
            position: relative
        }

        .DayPickerNavigation_button {
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            border: 0;
            padding: 0;
            margin: 0
        }

        .DayPickerNavigation_button__default {
            background-color: #fff;
            color: #757575
        }

        .DayPickerNavigation_button__default:active {
            background: #f2f2f2
        }

        .DayPickerNavigation_button__disabled {
            cursor: default
        }

        .DayPickerNavigation_button__disabled:active {
            background: 0 0
        }

        .DayPickerNavigation_button__horizontalDefault {
            position: absolute;
            top: 18px;
            line-height: .78;
            border-radius: 3px;
            padding: 6px 9px
        }

        .DayPickerNavigation_leftButton__horizontalDefault {
            left: 22px
        }

        .DayPickerNavigation_rightButton__horizontalDefault {
            right: 22px
        }

        .DayPickerNavigation_button__verticalDefault {
            padding: 5px;
            background: #fff;
            box-shadow: 0 0 5px 2px rgba(0, 0, 0, 0.1);
            position: relative;
            display: inline-block;
            text-align: center;
            height: 100%;
            width: 50%
        }

        .DayPickerNavigation_nextButton__verticalDefault {
            border-left: 0
        }

        .DayPickerNavigation_nextButton__verticalScrollableDefault {
            width: 100%
        }

        .DayPickerNavigation_svg__horizontal {
            height: 19px;
            width: 19px;
            fill: #82888a;
            display: block
        }

        .DayPickerNavigation_svg__vertical {
            height: 42px;
            width: 42px;
            fill: #484848
        }

        .DayPickerNavigation_svg__disabled {
            fill: #f2f2f2
        }

        .DayPicker {
            background: #fff;
            position: relative;
            text-align: left
        }

        .DayPicker__horizontal {
            background: #fff
        }

        .DayPicker__verticalScrollable {
            height: 100%
        }

        .DayPicker__hidden {
            visibility: hidden
        }

        .DayPicker__withBorder {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(0, 0, 0, 0.07);
            border-radius: 3px
        }

        .DayPicker_portal__horizontal {
            box-shadow: none;
            position: absolute;
            left: 50%;
            top: 50%
        }

        .DayPicker_portal__vertical {
            position: initial
        }

        .DayPicker_focusRegion {
            outline: 0
        }

        .DayPicker_calendarInfo__horizontal,
        .DayPicker_wrapper__horizontal {
            display: inline-block;
            vertical-align: top
        }

        .DayPicker_weekHeaders {
            position: relative
        }

        .DayPicker_weekHeaders__horizontal {
            margin-left: 9px
        }

        .DayPicker_weekHeader {
            color: #757575;
            position: absolute;
            top: 62px;
            z-index: 2;
            text-align: left
        }

        .DayPicker_weekHeader__vertical {
            left: 50%
        }

        .DayPicker_weekHeader__verticalScrollable {
            top: 0;
            display: table-row;
            border-bottom: 1px solid #dbdbdb;
            background: #fff;
            margin-left: 0;
            left: 0;
            width: 100%;
            text-align: center
        }

        .DayPicker_weekHeader_ul {
            list-style: none;
            margin: 1px 0;
            padding-left: 0;
            padding-right: 0;
            font-size: 14px
        }

        .DayPicker_weekHeader_li {
            display: inline-block;
            text-align: center
        }

        .DayPicker_transitionContainer {
            position: relative;
            overflow: hidden;
            border-radius: 3px
        }

        .DayPicker_transitionContainer__horizontal {
            -webkit-transition: height .2s ease-in-out;
            -moz-transition: height .2s ease-in-out;
            transition: height .2s ease-in-out
        }

        .DayPicker_transitionContainer__vertical {
            width: 100%
        }

        .DayPicker_transitionContainer__verticalScrollable {
            padding-top: 20px;
            height: 100%;
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            overflow-y: scroll
        }

        .DateInput {
            margin: 0;
            padding: 0;
            background: #fff;
            position: relative;
            display: inline-block;
            width: 130px;
            vertical-align: middle
        }

        .DateInput__small {
            width: 97px
        }

        .DateInput__block {
            width: 100%
        }

        .DateInput__disabled {
            background: #f2f2f2;
            color: #dbdbdb
        }

        .DateInput_input {
            font-weight: 200;
            font-size: 19px;
            line-height: 24px;
            color: #484848;
            background-color: #fff;
            width: 100%;
            padding: 11px 11px 9px;
            border: 0;
            border-top: 0;
            border-right: 0;
            border-bottom: 2px solid transparent;
            border-left: 0;
            border-radius: 0
        }

        .DateInput_input__small {
            font-size: 15px;
            line-height: 18px;
            letter-spacing: .2px;
            padding: 7px 7px 5px
        }

        .DateInput_input__regular {
            font-weight: auto
        }

        .DateInput_input__readOnly {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none
        }

        .DateInput_input__focused {
            outline: 0;
            background: #fff;
            border: 0;
            border-top: 0;
            border-right: 0;
            border-bottom: 2px solid #008489;
            border-left: 0
        }

        .DateInput_input__disabled {
            background: #f2f2f2;
            font-style: italic
        }

        .DateInput_screenReaderMessage {
            border: 0;
            clip: rect(0, 0, 0, 0);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px
        }

        .DateInput_fang {
            position: absolute;
            width: 20px;
            height: 10px;
            left: 22px;
            z-index: 2
        }

        .DateInput_fangShape {
            fill: #fff
        }

        .DateInput_fangStroke {
            stroke: #dbdbdb;
            fill: transparent
        }

        .DateRangePickerInput {
            background-color: #fff;
            display: inline-block
        }

        .DateRangePickerInput__disabled {
            background: #f2f2f2
        }

        .DateRangePickerInput__withBorder {
            border-radius: 2px
        }

        .DateRangePickerInput__rtl {
            direction: rtl
        }

        .DateRangePickerInput__block {
            display: block
        }

        .DateRangePickerInput__showClearDates {
            padding-right: 30px
        }

        .DateRangePickerInput_arrow {
            display: inline-block;
            vertical-align: middle;
            color: #484848
        }

        .DateRangePickerInput_arrow_svg {
            vertical-align: middle;
            fill: #484848;
            height: 24px;
            width: 24px
        }

        .DateRangePickerInput_clearDates {
            background: 0 0;
            border: 0;
            color: inherit;
            font: inherit;
            line-height: normal;
            overflow: visible;
            cursor: pointer;
            padding: 10px;
            margin: 0 10px 0 5px;
            position: absolute;
            right: 0;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%)
        }

        .DateRangePickerInput_clearDates__small {
            padding: 6px
        }

        .DateRangePickerInput_clearDates_default:focus,
        .DateRangePickerInput_clearDates_default:hover {
            background: #dbdbdb;
            border-radius: 50%
        }

        .DateRangePickerInput_clearDates__hide {
            visibility: hidden
        }

        .DateRangePickerInput_clearDates_svg {
            fill: #82888a;
            height: 12px;
            width: 15px;
            vertical-align: middle
        }

        .DateRangePickerInput_clearDates_svg__small {
            height: 9px
        }

        .DateRangePickerInput_calendarIcon {
            background: 0 0;
            border: 0;
            color: inherit;
            font: inherit;
            line-height: normal;
            overflow: visible;
            cursor: pointer;
            display: inline-block;
            vertical-align: middle;
            padding: 10px;
            margin: 0 5px 0 10px
        }

        .DateRangePickerInput_calendarIcon_svg {
            fill: #82888a;
            height: 15px;
            width: 14px;
            vertical-align: middle
        }

        .DateRangePicker {
            position: relative;
            display: inline-block
        }

        .DateRangePicker__block {
            display: block
        }

        .DateRangePicker_picker {
            z-index: 1;
            background-color: #fff;
            position: absolute
        }

        .DateRangePicker_picker__rtl {
            direction: rtl
        }

        .DateRangePicker_picker__directionLeft {
            left: 0
        }

        .DateRangePicker_picker__directionRight {
            right: 0
        }

        .DateRangePicker_picker__portal {
            background-color: rgba(0, 0, 0, 0.3);
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%
        }

        .DateRangePicker_picker__fullScreenPortal {
            background-color: #fff
        }

        .DateRangePicker_closeButton {
            background: 0 0;
            border: 0;
            color: inherit;
            font: inherit;
            line-height: normal;
            overflow: visible;
            cursor: pointer;
            position: absolute;
            top: 0;
            right: 0;
            padding: 15px;
            z-index: 2
        }

        .DateRangePicker_closeButton:focus,
        .DateRangePicker_closeButton:hover {
            color: #d5d5d5;
            text-decoration: none
        }

        .DateRangePicker_closeButton_svg {
            height: 15px;
            width: 15px;
            fill: #eee
        }

        .tag-manager-publicidade-banner_mobile1--visivel .search__ads__rect,
        .tag-manager-publicidade-banner_rm2--visivel .search__ads__rect {
            padding: 32px 0
        }

        @media (min-width: 941px) {

            .tag-manager-publicidade-banner_mobile1--visivel .search__ads__rect,
            .tag-manager-publicidade-banner_rm2--visivel .search__ads__rect {
                position: sticky;
                max-height: 600px;
                top: 0
            }
        }

        @media (min-width: 941px) {
            .tag-manager-publicidade-banner_slb_topo--visivel .search__ads__top {
                max-width: 1190px;
                background-color: #EBEDEF;
                margin: 0 auto 52px
            }
        }

        .widget.widget--info {
            display: flex;
            position: relative
        }

        .widget--info__ad-label {
            display: inline-block;
            margin-bottom: 7px;
            padding: 0 4px;
            font-family: ProximaNova-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 11px;
            line-height: 17px;
            letter-spacing: 0;
            color: #fff;
            text-transform: uppercase;
            background-color: #ffae00;
            border-radius: 3px;
            clear: both
        }

        .widget--info__text-container {
            flex: 1 0 0;
            position: relative;
            padding-bottom: 20px
        }

        .widget--info__header {
            font-family: OpenSans, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 14px;
            line-height: 14px;
            letter-spacing: 0;
            color: #060606;
            text-transform: uppercase;
            margin-bottom: 6px
        }

        .widget--info__title {
            font-family: ProximaNova-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 17px;
            line-height: 24px
        }

        .widget--info__title--ad {
            font-family: OpenSans-BoldItalic, Arial, "Helvetica Neue", Helvetica, sans-serif
        }

        .widget--info__description {
            font-family: OpenSans, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 13px;
            line-height: 18px;
            letter-spacing: 0;
            color: #666666
        }

        .widget--info__description em {
            font-family: OpenSans-BoldItalic, Arial, "Helvetica Neue", Helvetica, sans-serif
        }

        .widget--info__meta {
            position: absolute;
            bottom: 0;
            font-family: OpenSans, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 11px;
            line-height: 15px;
            letter-spacing: 0;
            color: #939393
        }

        .widget--info__meta--card {
            display: none
        }

        .widget--info__media-container {
            flex: 0 0 25%;
            min-width: 170px;
            margin-right: 14px
        }

        .widget--info__media {
            display: block;
            position: relative;
            height: 0;
            overflow: hidden;
            border-radius: 4px;
            padding-top: 56.25%;
            width: 100%;
            background: #ccc;
            box-shadow: inset 0 0 10px 0 rgba(0, 0, 0, 0.1)
        }

        .widget--info__media--video:before {
            content: " ";
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding-top: 56.25%;
            background-image: url(data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgNTEyIDUxMiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZmlsdGVyIGlkPSJhIj48ZmVHYXVzc2lhbkJsdXIgaW49IlNvdXJjZUFscGhhIiBzdGREZXZpYXRpb249IjIuNSIvPjxmZU9mZnNldCBkeT0iMSIgcmVzdWx0PSJvZmZzZXRibHVyIi8+PGZlRmxvb2QvPjxmZUNvbXBvc2l0ZSBpbjI9Im9mZnNldGJsdXIiIG9wZXJhdG9yPSJpbiIvPjxmZU1lcmdlPjxmZU1lcmdlTm9kZS8+PGZlTWVyZ2VOb2RlIGluPSJTb3VyY2VHcmFwaGljIi8+PC9mZU1lcmdlPjwvZmlsdGVyPjxwYXRoIGZpbGw9IiNGRkYiIGQ9Ik00NjguNzkgMjU1Ljk5OGMwIDguMTYtNC4yNCAxNS4yOTYtMTAuNTg1IDE5LjMwNXYuMDNMNzYuOTg1IDUwOC4xMjdsLS4wMDItLjAwM2MtMy4zNSAyLjA0NS03LjI1NiAzLjI0LTExLjQ0NSAzLjI0LTEyLjMzMyAwLTIyLjMzLTEwLjE3Ni0yMi4zMy0yMi43MyAwLS4wODguMDEtLjE3Mi4wMTItLjI2bC0uMDE0LS4wMDZWMjQuMzQzbC4wNDYtLjAzYy0uMDEyLS4zMTMtLjA0Ni0uNjItLjA0Ni0uOTM3IDAtMTIuNTU0IDkuOTk4LTIyLjczIDIyLjMzLTIyLjczIDQuMjM1IDAgOC4xOCAxLjIyIDExLjU1MyAzLjMwMmwuMDItLjAxMyAzODEuMTA2IDIzMi43NXYuMDE0YTIyLjc4MiAyMi43ODIgMCAwIDEgMTAuNTczIDE5LjI5NnoiIGZpbHRlcj0idXJsKCZxdW90OyNhJnF1b3Q7KSIvPjwvc3ZnPg==);
            background-size: 14%;
            background-repeat: no-repeat;
            background-position: 53% center;
            z-index: 3
        }

        .widget--info__media img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .widget--info__media-time {
            position: absolute;
            font-family: ProximaNova-Regular, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 11px;
            text-align: left;
            color: #fff;
            bottom: 7px;
            right: 14px;
            padding: 4px 10px;
            border-radius: 1px;
            background-color: rgba(35, 33, 33, 0.7)
        }

        @media (max-width: 480px) {
            .widget--info {
                flex-direction: column-reverse
            }

            .widget--info .match-results {
                order: 1
            }

            .widget--info .widget--info__header,
            .widget--info .widget--info__meta {
                display: none
            }

            .widget--info .widget--info__text-container {
                padding-bottom: 8px;
                margin-left: 0
            }

            .widget--info .widget--info__media {
                margin: 0 -16px 8px -16px;
                width: calc(100% + 32px);
                border-radius: 0
            }

            .widget--info .widget--info__media-container {
                margin-right: 0
            }

            .widget--info .widget--info__meta--card {
                display: block;
                font-family: OpenSans, Arial, "Helvetica Neue", Helvetica, sans-serif;
                text-transform: uppercase;
                margin-top: 4px
            }

            .widget--info .widget--info__meta--card span:nth-child(1) {
                font-size: 12px;
                color: #666666
            }

            .widget--info .widget--info__meta--card span:nth-child(2) {
                font-size: 12px;
                color: #d8d8d8
            }

            .widget--info .widget--info__meta--card span:nth-child(3) {
                font-size: 11px;
                color: #939393
            }
        }

        @media (min-width: 481px) {
            .results--videos .results__list {
                display: flex;
                flex-wrap: wrap;
                margin-left: -40px
            }

            .results--videos .results__list .widget--info {
                flex: 0 0 100%;
                flex-direction: column;
                border: 0;
                padding: 0 0 30px 40px
            }
        }

        @media (min-width: 481px) and (min-width: 481px) and (max-width: 767px) {
            .results--videos .results__list .widget--info {
                flex: 0 0 50%
            }
        }

        @media (min-width: 481px) and (min-width: 768px) and (max-width: 940px) {
            .results--videos .results__list .widget--info {
                flex: 0 0 33%
            }
        }

        @media (min-width: 481px) and (min-width: 941px) {
            .results--videos .results__list .widget--info {
                flex: 0 0 20%
            }
        }

        @media (min-width: 481px) {
            .results--videos .results__list .widget--info__text-container {
                margin-left: 0
            }

            .results--videos .results__list .widget--info__description {
                display: none
            }

            .results--videos .results__list .widget--info__media {
                margin-bottom: 12px
            }

            .results--videos .results__list .widget--info__meta {
                position: relative
            }
        }

        .widget--navigational__title {
            font-size: 17px;
            font-weight: bold;
            white-space: nowrap;
            text-overflow: ellipsis;
            display: block;
            width: 100%;
            overflow: hidden
        }

        @media (max-width: 480px) {
            .widget--navigational__title {
                font-size: 20px
            }
        }

        .widget--navigational__publisher {
            font-size: 11px;
            color: #939393;
            text-transform: uppercase;
            margin: 5px 0
        }

        @media (max-width: 480px) {
            .widget--navigational__publisher {
                margin: 10px 0
            }
        }

        .widget--navigational__description {
            font-size: 13px;
            line-height: 18px;
            color: #333
        }

        @media (max-width: 480px) {
            .widget--navigational__page {
                padding-bottom: 10px
            }
        }

        .widget--navigational__subpages {
            margin-top: 6px;
            padding-left: 6px;
            overflow: hidden
        }

        @media (max-width: 480px) {
            .widget--navigational__subpages {
                border-top: 1px solid #d9d9d9
            }
        }

        @media (max-width: 480px) {
            .widget--navigational__subpages .widget--navigational__title {
                font-size: 16px;
                line-height: 21px;
                width: calc(100% - 20px)
            }
        }

        @media (min-width: 481px) {
            .widget--navigational__subpages .widget--navigational__title {
                margin-bottom: 2px
            }
        }

        @media (max-width: 480px) {
            .widget--navigational__subpages .widget--navigational__description {
                display: none
            }
        }

        .widget--navigational__subpage {
            margin: 10px
        }

        @media (max-width: 480px) {
            .widget--navigational__subpage {
                margin: 20px 10px
            }

            .widget--navigational__subpage:after {
                content: " ";
                width: 12px;
                height: 12px;
                background-color: #fff;
                transform: rotate(45deg);
                border: 2px solid #A30400;
                border-bottom: 0;
                border-left: 0;
                float: right;
                display: block;
                margin: -16px 4px 0 0
            }
        }

        @media (min-width: 481px) {
            .widget--navigational__subpage {
                width: calc(50% - 20px);
                height: 72px;
                float: left
            }

            .widget--navigational__subpage .widget--navigational__title {
                font-size: 16px
            }

            .widget--navigational__subpage .widget--navigational__description {
                display: block
            }
        }

        .widget--no-results {
            text-align: center;
            color: #999;
            padding: 120px 0;
            margin-top: 20px
        }

        .widget--no-results__title {
            font-size: 2.5em;
            font-weight: lighter;
            letter-spacing: -0.02em;
            line-height: 1em;
            margin: 20px 0 10px
        }

        @media (max-width: 480px) {
            .widget--no-results__title {
                font-size: 1.5em
            }
        }

        .widget--no-results__action {
            font-size: 17px;
            font-family: ProximaNova-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif
        }

        @media (max-width: 480px) {
            .widget--no-results__action {
                font-size: 12px;
                font-family: inherit;
                margin: 20px 0 10px
            }
        }

        .widget--no-results__link {
            display: inline
        }

        @media (max-width: 480px) {
            .widget--no-results__link {
                display: block;
                font-weight: bold
            }
        }

        .widget--quotes {
            padding: 0px
        }

        .widget--quotes__variation {
            font-family: OpenSans;
            text-align: center;
            line-height: 1.2
        }

        .widget--quotes__variation__positive {
            color: #6ebf18;
            font-size: 20px
        }

        .widget--quotes__variation__negative {
            color: #ca1f09;
            font-size: 20px
        }

        .widget--quotes__data {
            display: flex;
            justify-content: flex-start;
            align-items: flex-end;
            margin-top: 24px
        }

        .widget--quotes__quote_item {
            margin-right: 16px
        }

        .widget--quotes__label {
            font-family: OpenSans;
            font-size: 10px;
            line-height: 2;
            text-transform: uppercase;
            color: #555555
        }

        .widget--quotes__title {
            display: flex;
            flex-direction: column;
            font-family: OpenSans;
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            font-stretch: normal;
            line-height: 1.25
        }

        .widget--quotes__title a:link,
        .widget--quotes__title a:visited,
        .widget--quotes__title a:hover,
        .widget--quotes__title a:active {
            color: inherit
        }

        .widget--quotes__title a:link span,
        .widget--quotes__title a:visited span,
        .widget--quotes__title a:hover span,
        .widget--quotes__title a:active span {
            vertical-align: middle
        }

        .widget--quotes__last-update {
            font-family: OpenSans;
            font-size: 12px;
            line-height: 1;
            color: #555555;
            margin-top: 24px
        }

        .widget--quotes__view-more {
            font-family: OpenSans;
            font-size: 14px;
            font-weight: bold;
            line-height: 1.4;
            margin-top: 24px
        }

        .widget--quotes__view-more a:link,
        .widget--quotes__view-more a:visited,
        .widget--quotes__view-more a:hover,
        .widget--quotes__view-more a:active {
            color: inherit
        }

        .widget--quotes__view-more a:link span,
        .widget--quotes__view-more a:visited span,
        .widget--quotes__view-more a:hover span,
        .widget--quotes__view-more a:active span {
            vertical-align: middle
        }

        .widget--quotes__info {
            font-family: OpenSans;
            font-size: 20px;
            line-height: 1.2;
            color: #555555
        }

        .widget--multiple--quotes__table {
            width: 100%;
            margin-top: 18px
        }

        .widget--multiple--quotes__table th,
        .widget--multiple--quotes__table td {
            padding-top: 5px;
            padding-bottom: 5px
        }

        .widget--multiple--quotes__coin-label {
            font-family: OpenSans;
            font-size: 10px;
            line-height: 2;
            text-transform: uppercase;
            font-weight: normal;
            font-style: normal;
            font-stretch: normal;
            color: #555555;
            width: 40%
        }

        .widget--multiple--quotes__data-label {
            font-family: OpenSans;
            font-size: 10px;
            line-height: 2;
            text-transform: uppercase;
            font-weight: normal;
            font-style: normal;
            font-stretch: normal;
            color: #555555;
            width: 20%;
            text-align: right
        }

        .widget--multiple--quotes__coin {
            font-family: OpenSans;
            font-size: 13px;
            font-weight: 600;
            font-style: normal;
            font-stretch: normal;
            line-height: 1.15
        }

        .widget--multiple--quotes__coin a:link,
        .widget--multiple--quotes__coin a:visited,
        .widget--multiple--quotes__coin a:hover,
        .widget--multiple--quotes__coin a:active {
            color: inherit
        }

        .widget--multiple--quotes__coin a:link span,
        .widget--multiple--quotes__coin a:visited span,
        .widget--multiple--quotes__coin a:hover span,
        .widget--multiple--quotes__coin a:active span {
            vertical-align: middle
        }

        .widget--multiple--quotes__date {
            font-family: OpenSans;
            font-size: 14px;
            font-weight: normal;
            font-style: normal;
            font-stretch: normal;
            line-height: 1.43;
            text-align: right;
            color: #555555;
            width: 20%
        }

        .widget--multiple--quotes__variation {
            font-family: OpenSans
        }

        .widget--multiple--quotes__variation__positive {
            color: #6ebf18;
            font-size: 14px;
            width: 20%;
            text-align: right
        }

        .widget--multiple--quotes__variation__negative {
            color: #ca1f09;
            font-size: 14px;
            width: 20%;
            text-align: right
        }

        .arrow-up {
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-bottom: 16px solid #6ebf18;
            margin-right: 5px
        }

        .arrow-down {
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-top: 16px solid #ca1f09;
            margin-right: 5px
        }

        .flex-row {
            display: flex;
            justify-content: flex-start;
            align-items: center
        }

        .widget--live__title {
            display: block;
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 4px
        }

        .widget--live__description {
            font-size: 13px;
            line-height: 18px;
            color: #333
        }

        .widget--live__live-label {
            background-color: #f00;
            display: inline-block;
            margin-bottom: 7px;
            padding: 6px 8px;
            font-family: ProximaNova-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 10px;
            line-height: 10px;
            letter-spacing: 0;
            color: #fff;
            text-transform: uppercase;
            border-radius: 3px;
            clear: both
        }

        .widget--live__live-label__text {
            animation: live-blink 2s infinite;
            display: inline-block;
            vertical-align: middle
        }

        .widget.widget--live-match {
            padding: 0
        }

        .widget--live-match {
            border: 1px solid #e6e6e6;
            border-radius: 4px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05)
        }

        .widget--live-match__link {
            display: block;
            overflow: hidden;
            padding: 24px
        }

        .widget--live-match__live-label {
            display: inline-block;
            margin-right: 24px;
            padding: 6px 8px;
            border-radius: 3px;
            background-color: #37ab00;
            color: #fff;
            font-family: ProximaNova-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 10px;
            letter-spacing: 0;
            line-height: 10px;
            text-transform: uppercase;
            vertical-align: top
        }

        .widget--live-match__live-label__text {
            display: inline-block;
            animation: live-blink 2s infinite;
            vertical-align: middle
        }

        .widget--live-match__head {
            margin-bottom: 10px;
            clear: both;
            overflow: hidden
        }

        .widget--live-match__championship,
        .widget--live-match__period {
            color: #333;
            font-family: OpenSans, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 18px;
            line-height: 22px;
            vertical-align: middle
        }

        .widget--live-match__period {
            float: right
        }

        .widget--live-match__meta {
            color: #999;
            font-family: ProximaNova-Regular, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 12px;
            line-height: 12px;
            text-transform: uppercase
        }

        .widget--live-match .match-results__flag {
            flex: 0 0 45px;
            width: 45px;
            height: 45px;
            margin: 0 8px;
            vertical-align: middle
        }

        @media (min-width: 481px) {
            .widget--live-match .match-results__team {
                flex-grow: 1
            }

            .widget--live-match .match-results__team:nth-child(2) {
                text-align: left
            }

            .widget--live-match .match-results__team:nth-child(5) {
                text-align: right
            }

            .widget--live-match .match-results__name {
                flex: 1 0 0;
                color: #333;
                font-family: OpenSans, Arial, "Helvetica Neue", Helvetica, sans-serif;
                font-size: 28px;
                text-transform: none
            }

            .widget--live-match .match-results__name--acronym {
                display: none
            }

            .widget--live-match .match-results__name--full {
                display: block
            }

            .widget--live-match .match-results__score {
                flex: 0 1 0;
                font-size: 58px;
                line-height: 64px;
                min-width: 95px
            }

            .widget--live-match .match-results__score-penalty {
                font-size: 30px;
                line-height: 64px;
                min-width: 40px
            }
        }

        @media (max-width: 480px) {
            .widget--live-match__live-label {
                margin-right: 10px
            }

            .widget--live-match__championship,
            .widget--live-match__period {
                font-size: 16px
            }

            .widget--live-match__period {
                display: block;
                clear: both;
                width: 100%;
                text-align: center;
                margin-top: 12px
            }

            .widget--live-match .match-results__name {
                font-size: 16px
            }

            .widget--live-match .match-results__name--acronym {
                display: block
            }

            .widget--live-match .match-results__name--full {
                display: none
            }

            .widget--live-match .match-results__score {
                font-size: 26px
            }

            .widget--live-match .match-results__score-penalty {
                font-size: 16px;
                margin: 0 3px
            }
        }

        .widget--grouped-info__title {
            display: block;
            border-bottom: 1px solid #e9e9e9;
            width: calc(100% + 32px);
            margin: -16px -16px 0 -16px;
            padding: 16px;
            font-family: OpenSans, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 16px;
            font-weight: bold;
            line-height: 1.38;
            letter-spacing: -1px;
            text-align: left;
            color: #333
        }

        .widget--grouped-info__view-more {
            display: block;
            clear: both;
            font-family: OpenSans, Arial, "Helvetica Neue", Helvetica, sans-serif;
            padding-top: 16px;
            font-size: 14px;
            font-weight: bold;
            line-height: 1.43;
            letter-spacing: -0.7px
        }

        .widget--grouped-info-item {
            display: flex;
            position: relative;
            padding: 16px 0;
            border-bottom: 1px solid #e9e9e9
        }

        .widget--grouped-info-item__text-container {
            flex: 1 0 0;
            position: relative;
            padding-bottom: 20px
        }

        .widget--grouped-info-item__text-container:not(:last-child) {
            margin-right: 14px
        }

        .widget--grouped-info-item__title {
            font-family: ProximaNova-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 17px;
            line-height: 24px
        }

        .widget--grouped-info-item__meta {
            position: absolute;
            bottom: 0;
            font-family: OpenSans, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 11px;
            line-height: 15px;
            letter-spacing: 0;
            color: #939393
        }

        .widget--grouped-info-item__meta--card {
            display: none
        }

        .widget--grouped-info-item__media-container {
            flex: 0 0 25%
        }

        .widget--grouped-info-item__media {
            display: block;
            position: relative;
            height: 0;
            overflow: hidden;
            padding-top: 100%;
            width: 100%;
            background: #ccc;
            box-shadow: inset 0 0 10px 0 rgba(0, 0, 0, 0.1)
        }

        .widget--grouped-info-item__media img {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%
        }

        .match-results {
            margin-bottom: 16px;
            min-width: 170px;
            margin-right: 14px
        }

        .match-results__head {
            display: block;
            clear: both;
            font-size: 14px;
            line-height: 14px;
            letter-spacing: -0.8px;
            font-family: OpenSans, Arial, "Helvetica Neue", Helvetica, sans-serif;
            text-transform: capitalize;
            color: #666;
            margin-bottom: 16px
        }

        .match-results__result-container {
            display: flex;
            position: relative;
            align-items: center;
            clear: both
        }

        .match-results__team {
            display: flex;
            position: relative;
            align-items: center
        }

        .match-results__name {
            font-family: ProximaNova-Regular, Arial, "Helvetica Neue", Helvetica, sans-serif;
            text-transform: uppercase;
            color: #888;
            font-size: 14px
        }

        .match-results__flag {
            flex: 0 0 30px;
            margin: 0 8px;
            width: 30px;
            height: 30px;
            vertical-align: middle
        }

        .match-results__versus {
            font-family: OpenSans-Light, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 18px;
            line-height: 24px;
            text-align: center;
            padding: 0 6px;
            color: #999;
            display: inline-block
        }

        .match-results__versus svg {
            display: block;
            width: 12px;
            height: 12px
        }

        .match-results__score {
            flex: 1 0 0;
            color: #333;
            font-family: OpenSans-Bold, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 24px;
            line-height: 24px;
            display: inline-block;
            min-width: 42px;
            font-weight: bold;
            white-space: nowrap
        }

        .match-results__score:nth-child(2) {
            text-align: right
        }

        .match-results__score:nth-child(5) {
            text-align: left
        }

        .match-results__score-penalty {
            display: inline-block;
            font-size: 16px;
            line-height: 24px;
            letter-spacing: -1.2px;
            vertical-align: top
        }

        .match-results__team:nth-child(1) {
            flex-direction: row-reverse
        }

        .match-results__team:nth-child(5) {
            flex-direction: row
        }

        @media (min-width: 481px) {
            .widget--info .match-results {
                flex: 0 0 25%
            }

            .widget--info .match-results__wrapper {
                display: block;
                position: relative;
                height: 0;
                overflow: hidden;
                border-radius: 4px;
                padding-top: 56.25%;
                width: 100%
            }

            .widget--info .match-results__head {
                text-align: center;
                position: absolute;
                top: 0;
                width: 100%
            }

            .widget--info .match-results__result-container {
                position: absolute;
                top: 30px;
                width: 100%;
                height: calc(100% - 30px)
            }

            .widget--info .match-results__team {
                flex-direction: column
            }

            .widget--info .match-results__name {
                margin-top: 14px
            }

            .widget--info .match-results__score,
            .widget--info .match-results__versus {
                margin-bottom: 28px
            }
        }

        @media (max-width: 480px) {
            .widget--info .match-results {
                margin-right: 0px
            }
        }

        .widget {
            display: block;
            padding: 32px 0;
            border-bottom: 1px solid #e9e9e9
        }

        .widget.filters {
            padding: 0
        }

        @media (max-width: 480px) {
            .widget {
                background-color: #fff;
                margin-bottom: 12px;
                padding: 16px;
                box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.1);
                border: 0;
                clear: both
            }

            .widget--card {
                border-radius: 3px
            }

            .widget.filters {
                padding: 0 16px
            }
        }

        .results__list .widget:last-child,
        .pagination.widget {
            border-bottom: 0
        }
        .results__content{
            padding-left: 20px;
            padding-right: 10px;
        }
    </style>


    <div id="main-wrapper" x-data="app">
        <div class="row"> 
            <div class=" col-xs-12 col-sm-12 col-md-6 ">
                <div class="panel panel-white">
                    @if($noticias['moc'])
                        {!! $noticias['moc'] !!}              
                    @else 
                        <div style="text-align: center; width: 100%; height:600px;"> 
                            <br><br><br><br><br>
                            <img src="{{asset('assets\images\gps.png') }}"  ><br>
                            <h5>Localidade não Configurada!</h5>
                        </div>
                        
                    @endif
                </div>
            </div>
            <div class=" col-xs-12 col-sm-12 col-md-6 ">
                <div class="panel panel-white">
                    {!! $noticias['saude'] !!}              
                </div>
            </div>
        </div>


    </div>
@endsection
