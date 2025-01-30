<template>
    <div>
      <p v-if="!user.email_verified_at">
        بريدك الإلكتروني غير مفعل. يرجى التحقق من بريدك الإلكتروني.
      </p>
      <button @click="resendVerificationEmail">إعادة إرسال رابط التحقق</button>
      <p v-if="message">{{ message }}</p>
    </div>
  </template>

  <script>
  import axios from 'axios';

  export default {
    data() {
      return {
        user: {}, // بيانات المستخدم
        message: '',
      };
    },
    methods: {
      async resendVerificationEmail() {
        try {
          const response = await axios.post('/api/email/resend', {}, {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('token')}`, // استخدم التوكن
            },
          });
          this.message = response.data.message;
        } catch (error) {
          this.message = 'حدث خطأ أثناء إرسال رابط التحقق.';
        }
      },
    },
    mounted() {
      // استرداد بيانات المستخدم
      this.user = JSON.parse(localStorage.getItem('user'));
    },
  };
  </script>
