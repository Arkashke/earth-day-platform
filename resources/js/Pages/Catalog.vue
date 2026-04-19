<template>
  <div>
    <InertiaHead title="Каталог инициатив" />

    <!-- Page Header -->
    <div class="page-header">
      <div class="container">
        <div class="breadcrumbs">
          <InertiaLink href="/">Главная</InertiaLink>
          <span>/</span>
          <span>Каталог инициатив</span>
        </div>
        <h1>Каталог экологических инициатив</h1>
        <p style="opacity:0.85;margin-top:8px;font-size:17px;">
          Проекты юридических лиц, реализующих экологические инициативы по всей России
        </p>
      </div>
    </div>

    <div class="container" style="padding-bottom:80px;">
      <div style="display:grid;grid-template-columns:280px 1fr;gap:32px;align-items:start;">
        <!-- SIDEBAR FILTERS -->
        <aside style="position:sticky;top:90px;">
          <div style="background:white;border-radius:var(--radius-lg);padding:20px;box-shadow:var(--shadow-sm);border:1px solid var(--color-border);">
            <h3 style="font-family:var(--font-heading);font-size:16px;font-weight:700;margin-bottom:16px;">
              🔍 Фильтры
            </h3>

            <!-- Поиск -->
            <div class="form-group">
              <label class="form-label">Поиск</label>
              <input
                v-model="filters.search"
                type="text"
                class="form-control"
                placeholder="Название проекта..."
                @input="debouncedFetch"
              />
            </div>

            <!-- Номинация -->
            <div class="form-group">
              <label class="form-label">Номинация</label>
              <select v-model="filters.nomination_id" class="form-control" @change="fetchProjects">
                <option value="">Все номинации</option>
                <optgroup v-for="cat in nominationCategories" :key="cat.id" :label="cat.title">
                  <option v-for="n in cat.nominations" :key="n.id" :value="n.id">{{ n.name }}</option>
                </optgroup>
              </select>
            </div>

            <!-- Тип организации -->
            <div class="form-group">
              <label class="form-label">Тип организации</label>
              <select v-model="filters.organization_type_id" class="form-control" @change="fetchProjects">
                <option value="">Все типы</option>
                <option v-for="t in organizationTypes" :key="t.id" :value="t.id">{{ t.name }}</option>
              </select>
            </div>

            <!-- Регион -->
            <div class="form-group">
              <label class="form-label">Регион</label>
              <select v-model="filters.region_id" class="form-control" @change="fetchProjects">
                <option value="">Все регионы</option>
                <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.name }}</option>
              </select>
            </div>

            <!-- Статус -->
            <div class="form-group">
              <label class="form-label">Статус</label>
              <select v-model="filters.status" class="form-control" @change="fetchProjects">
                <option value="">Все статусы</option>
                <option value="published">Активен</option>
                <option value="completed">Завершён</option>
              </select>
            </div>

            <button @click="resetFilters" class="btn btn-secondary btn-sm btn-block">
              🔄 Сбросить фильтры
            </button>
          </div>

          <!-- Карта -->
          <div style="background:white;border-radius:var(--radius-lg);padding:20px;margin-top:16px;box-shadow:var(--shadow-sm);border:1px solid var(--color-border);">
            <h3 style="font-family:var(--font-heading);font-size:16px;font-weight:700;margin-bottom:12px;">🗺️ Карта</h3>
            <div style="background:#e8f5e9;border-radius:var(--radius-md);height:200px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;">
              <div style="font-size:32px;">🗺️</div>
              <p style="font-size:12px;color:var(--color-text-muted);text-align:center;">Яндекс.Карты</p>
              <InertiaLink href="/catalog?map=true" class="btn btn-primary btn-sm">Открыть карту</InertiaLink>
            </div>
          </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div>
          <!-- Sort + Count -->
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <p style="font-size:14px;color:var(--color-text-secondary);">
              Найдено: <strong>{{ total }}</strong> проектов
            </p>
            <div style="display:flex;gap:8px;align-items:center;">
              <label style="font-size:14px;color:var(--color-text-secondary);">Сортировка:</label>
              <select v-model="filters.sort" class="form-control" style="width:auto;" @change="fetchProjects">
                <option value="newest">Сначала новые</option>
                <option value="popular">По популярности</option>
              </select>
            </div>
          </div>

          <!-- Grid -->
          <div v-if="projects.length > 0" class="cards-grid">
            <div v-for="project in projects" :key="project.id" class="project-card">
              <img
                :src="project.image || '/img/placeholder-project.jpg'"
                :alt="project.title"
                class="project-card__image"
              />
              <div class="project-card__body">
                <div class="project-card__org">
                  <img
                    :src="project.organization?.logo || '/img/default-org.svg'"
                    class="project-card__org-logo"
                    :alt="project.organization?.short_name"
                  />
                  <span class="project-card__org-name">{{ project.organization?.short_name }}</span>
                </div>
                <h3 class="project-card__title">{{ project.title }}</h3>
                <p class="project-card__excerpt">{{ project.excerpt }}</p>
                <div style="margin-top:12px;display:flex;gap:8px;flex-wrap:wrap;">
                  <span class="tag">{{ project.nomination?.name }}</span>
                  <span v-if="project.region" class="tag" style="background:#e3f2fd;color:#1565c0;">
                    📍 {{ project.region?.name }}
                  </span>
                  <span :class="`project-card__badge badge-${project.status_key}`">
                    {{ project.status_label }}
                  </span>
                </div>
              </div>
              <div class="project-card__footer">
                <span>{{ project.published_at }}</span>
                <InertiaLink :href="`/catalog/${project.id}`" class="btn btn-primary btn-sm">
                  Подробнее →
                </InertiaLink>
              </div>
            </div>
          </div>

          <!-- Empty state -->
          <div v-else style="text-align:center;padding:80px 0;">
            <div style="font-size:64px;margin-bottom:16px;">🔍</div>
            <h3 style="font-family:var(--font-heading);font-size:20px;margin-bottom:8px;">Проекты не найдены</h3>
            <p style="color:var(--color-text-secondary);margin-bottom:24px;">
              Попробуйте изменить параметры поиска или сбросить фильтры
            </p>
            <button @click="resetFilters" class="btn btn-primary">Сбросить фильтры</button>
          </div>

          <!-- Pagination -->
          <div v-if="lastPage > 1" class="pagination">
            <button v-if="currentPage > 1" @click="goToPage(currentPage - 1)" class="btn btn-sm btn-secondary">
              ←
            </button>
            <template v-for="p in visiblePages" :key="p">
              <button
                v-if="p !== '...'"
                @click="goToPage(p)"
                :class="['pagination__btn', { active: p === currentPage }]"
                style="min-width:40px;height:40px;border-radius:6px;border:1px solid var(--color-border);background:white;font-size:14px;font-weight:600;cursor:pointer;"
                :style="p === currentPage ? 'background:var(--color-primary);color:white;border-color:var(--color-primary);' : ''"
              >
                {{ p }}
              </button>
              <span v-else style="padding:0 8px;color:var(--color-text-muted);">...</span>
            </template>
            <button v-if="currentPage < lastPage" @click="goToPage(currentPage + 1)" class="btn btn-sm btn-secondary">
              →
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

defineProps({
  projects: { type: Array, default: () => [] },
  nominationCategories: { type: Array, default: () => [] },
  organizationTypes: { type: Array, default: () => [] },
  regions: { type: Array, default: () => [] },
  total: { type: Number, default: 0 },
  currentPage: { type: Number, default: 1 },
  lastPage: { type: Number, default: 1 },
});

const filters = ref({
  search: '',
  nomination_id: '',
  organization_type_id: '',
  region_id: '',
  status: '',
  sort: 'newest',
});

function fetchProjects() {
  router.get('/catalog', { ...filters.value }, { preserveState: true });
}

let debounceTimer = null;
function debouncedFetch() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(fetchProjects, 400);
}

function resetFilters() {
  filters.value = {
    search: '',
    nomination_id: '',
    organization_type_id: '',
    region_id: '',
    status: '',
    sort: 'newest',
  };
  fetchProjects();
}

function goToPage(page) {
  router.get('/catalog', { ...filters.value, page }, { preserveState: true });
}

const visiblePages = computed(() => {
  const current = 1; // заменить на currentPage из пропсов
  const last = 10;    // заменить на lastPage
  const delta = 2;
  const range = [];
  for (let i = Math.max(1, current - delta); i <= Math.min(last, current + delta); i++) {
    range.push(i);
  }
  if (range[0] > 1) range.unshift('...');
  if (range[range.length - 1] < last) range.push('...');
  return range;
});
</script>
