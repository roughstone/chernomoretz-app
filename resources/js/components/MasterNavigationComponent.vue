<!-- Navigation of the app vuejs router and bootstrap packages used. -->
<template>
<nav id="navBar" class="navbar navbar-expand-lg navbar-dark">

<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <i class="fas fa-bars fa-3x"></i>
</button>

<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
    <li class="nav-item"><router-link class="nav-link pt-0 pb-0" to="/"><i class="fas fa-home purple"></i> Начало</router-link></li>
    <li class="nav-item"><router-link class="nav-link pt-0 pb-0" to="/Новини/1"><i class="far fa-newspaper purple"></i> Новини</router-link></li>
    <li class="nav-item dropdown">
        <a class="nav-link pt-0 pb-0 dropdown-toggle" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-users purple"></i> Отбор
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <router-link class="dropdown-item" to="/Треньори">Треньори</router-link>
            <router-link class="dropdown-item" to="/Състезатели/1">Състезатели</router-link>
        </div>
    </li>
    <li class="nav-item"><router-link class="nav-link pt-0 pb-0" to="/График"><i class="fas fa-clipboard-list purple"></i> График</router-link></li>
    <li class="nav-item dropdown"><a class="nav-link pt-0 pb-0 dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="far fa-images purple"></i> Галерия
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <router-link class="dropdown-item" to="/Галерии/Състезания/Страница/1">Стстезания</router-link>
            <router-link class="dropdown-item" to="/Галерии/Събития/Страница/1">Събития</router-link>
            <router-link class="dropdown-item" to="/Галерии/Тренировки/Страница/1">Тренировки</router-link>
        </div>
    </li>
    <li class="nav-item">
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a>
    and wich calls "openContactsModal" method. -->
        <a href="" @click.prevent="openContactsModal()" class="nav-link pt-0 pb-0" to="/Контакти"><i class="fas fa-phone-square purple"></i> Контакти с нас!</a>
    </li>
    </ul>
</div>
</nav>
</template>

<script>
    export default {
        methods: {
            openContactsModal() { // display contacts component based in route parameter contactsForm condition
                if(this.$route.params.contactsForm) {
                    this.$route.params.contactsForm = false
                } else {
                    this.$route.params.contactsForm = true
                }
            }
        },
        mounted() {
            this.$route.params.contactsForm = false //when component is mounted push contactsForm parameter to the router and set id to false.
        }
    }
</script>
