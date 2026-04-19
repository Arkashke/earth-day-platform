<template>
  <div class="page-wrapper">
    <!-- Header -->
    <header class="site-header">
      <div class="container">
        <div class="header-inner">
          <InertiaLink href="/" class="site-logo">
            <img src="/img/logo.svg" alt="День Земли" />
            <span>День Земли</span>
          </InertiaLink>

          <nav class="main-nav">
            <InertiaLink href="/about" class="nav-link">О проекте</InertiaLink>
            <InertiaLink href="/catalog" class="nav-link">Каталог</InertiaLink>
            <InertiaLink href="/events" class="nav-link">Съезд</InertiaLink>
            <InertiaLink href="/recognition" class="nav-link">Награды</InertiaLink>
            <InertiaLink href="/news" class="nav-link">Новости</InertiaLink>
            <InertiaLink href="/partners" class="nav-link">Партнёрам</InertiaLink>
            <InertiaLink href="/resources" class="nav-link">Ресурсы</InertiaLink>
            <InertiaLink href="/contacts" class="nav-link">Контакты</InertiaLink>
          </nav>

          <div class="header-actions">
            <template v-if="$page.props.auth?.user">
              <InertiaLink href="/dashboard" class="btn btn-primary btn-sm">
                Личный кабинет
              </InertiaLink>
            </template>
            <template v-else>
              <InertiaLink href="/login" class="btn btn-secondary btn-sm">Войти</InertiaLink>
              <InertiaLink href="/register" class="btn btn-primary btn-sm">Регистрация</InertiaLink>
            </template>
          </div>
        </div>
      </div>
    </header>

    <!-- Main content -->
    <main class="main-content">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="site-footer">
      <div class="container">
        <div class="footer-grid">
          <div class="footer-col">
            <div class="site-logo" style="color:white;margin-bottom:12px;">
              <span>🌍 День Земли</span>
            </div>
            <p style="font-size:14px;color:#aaa;line-height:1.7;max-width:300px;">
              Платформа для объединения юридических лиц, реализующих экологические инициативы. Каждый день — шаг к устойчивому будущему.
            </p>
          </div>
          <div class="footer-col">
            <h4>Навигация</h4>
            <ul>
              <li><InertiaLink href="/about">О проекте</InertiaLink></li>
              <li><InertiaLink href="/catalog">Каталог инициатив</InertiaLink></li>
              <li><InertiaLink href="/events">Съезд</InertiaLink></li>
              <li><InertiaLink href="/recognition">Награды</InertiaLink></li>
              <li><InertiaLink href="/participate">Как участвовать</InertiaLink></li>
            </ul>
          </div>
          <div class="footer-col">
            <h4>Информация</h4>
            <ul>
              <li><InertiaLink href="/news">Новости</InertiaLink></li>
              <li><InertiaLink href="/resources">База знаний</InertiaLink></li>
              <li><InertiaLink href="/media">Медиа</InertiaLink></li>
              <li><InertiaLink href="/faq">FAQ</InertiaLink></li>
              <li><InertiaLink href="/contacts">Контакты</InertiaLink></li>
            </ul>
          </div>
          <div class="footer-col">
            <h4>Контакты</h4>
            <ul>
              <li>📍 г. Москва, ул. Примерная, д. 1</li>
              <li>📞 +7 (495) 123-45-67</li>
              <li>✉️ info@earthday.ru</li>
            </ul>
            <div style="margin-top:16px;display:flex;gap:10px;">
              <a href="#" class="social-link" style="color:#aaa;font-size:20px;">🔵</a>
              <a href="#" class="social-link" style="color:#aaa;font-size:20px;">🟢</a>
              <a href="#" class="social-link" style="color:#aaa;font-size:20px;">📺</a>
            </div>
          </div>
        </div>
        <div class="footer-bottom">
          <span>© {{ new Date().getFullYear() }} Оргкомитет «День Земли — каждый день!»</span>
          <div style="display:flex;gap:20px;">
            <InertiaLink href="/privacy" style="color:#888;">Политика конфиденциальности</InertiaLink>
            <InertiaLink href="/offer" style="color:#888;">Договор оферты</InertiaLink>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
// Лейаут применяется ко всем публичным страницам
</script>
