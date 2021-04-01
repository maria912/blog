<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
               <passport-Clients></passport-Clients>
               <passport-Authorized-Clients></passport-Authorized-Clients>
               <passport-Personal-Access-Tokens></passport-Personal-Access-Tokens>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
