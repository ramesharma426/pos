<template>
    <div>
        <div class="hold-transition login-page">
            <div class="login-box">
                <div class="card card-outline card-primary">
                    <div class="card-header text-center">
                        <h1>POS</h1>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="handleSubmit">
                            <div class="input-group mb-3">
                                <input type="email" v-model="email" class="form-control" placeholder="Email" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <input type="password" v-model="password" class="form-control" id="password"
                                    placeholder="Password" required>
                                <div class="input-group-append">
                                    <div @click="togglePassword" class="input-group-text">
                                        <span class="fas fa-eye" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-8">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="remember" v-model="remember">
                                        <label for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block mt-2">Sign In</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.login-box -->
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import postLogin from '../../../composables/auth/postLogin'
import { useRouter } from 'vue-router'
import $ from 'jquery'

const router = useRouter()
const { login } = postLogin()
const email = ref('')
const password = ref('')
const remember = ref(false)

const togglePassword = () => {
    let input = $('#password')
    if (input.attr('type') === 'password') {
        input.attr('type', 'text')
    } else {
        input.attr('type', 'password')
    }

}

const handleSubmit = () => {
    const credentials = {
        email: email.value,
        password: password.value,
        remember: remember.value
    }
    login(credentials).then(() => {
        router.push({ name: 'dashboard' })
    }).catch()
}
</script>

<style scoped>
.input-group-text:hover {
    cursor: pointer;
}
</style>
