<template>
<nav class="navbar navbar-expand-lg navbar-dark">

<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
    <li class="nav-item">
        <router-link class="nav-link" to="/#"><i class="fas fa-home purple"></i> Начало</router-link>
    </li>
    <li class="nav-item">
        <router-link class="nav-link" to="/Новини"><i class="far fa-newspaper purple"></i> Новини</router-link>
    </li>

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-users purple"></i> Отбор
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <router-link class="dropdown-item" to="/Треньори">Треньори</router-link>
            <router-link class="dropdown-item" to="/Състезатели">Състезатели</router-link>
            <router-link class="dropdown-item" to="/График">График</router-link>
        </div>
    </li>

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="far fa-images purple"></i> Галерия
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <router-link class="dropdown-item" to="/Стстезания">Стстезания</router-link>
            <router-link class="dropdown-item" to="/Събития">Събития</router-link>
            <router-link class="dropdown-item" to="/Тренировки">Тренировки</router-link>
        </div>
    </li>
    <li class="nav-item">
        <router-link class="nav-link" to="/Контакти"><i class="fas fa-phone-square purple"></i> Контакти с нас!</router-link>
    </li>
    </ul>
</div>
</nav>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
