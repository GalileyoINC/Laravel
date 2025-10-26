<template>
  <div class="alert-map-container">
    <!-- Map Controls -->
    <div class="map-controls mb-4 flex flex-wrap gap-2">
      <select 
        v-model="selectedSeverity" 
        @change="loadAlerts"
        class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
      >
        <option value="">All Severities</option>
        <option value="critical">Critical</option>
        <option value="high">High</option>
        <option value="medium">Medium</option>
        <option value="low">Low</option>
      </select>

      <select 
        v-model="selectedCategory" 
        @change="loadAlerts"
        class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
      >
        <option value="">All Categories</option>
        <option value="emergency">Emergency</option>
        <option value="weather">Weather</option>
        <option value="security">Security</option>
        <option value="traffic">Traffic</option>
        <option value="medical">Medical</option>
        <option value="fire">Fire</option>
        <option value="police">Police</option>
      </select>

      <button 
        @click="loadAlerts"
        :disabled="loading"
        class="rounded-lg bg-blue-500 px-4 py-2 text-sm text-white hover:bg-blue-600 disabled:opacity-50"
      >
        {{ loading ? 'Loading...' : 'Refresh' }}
      </button>

      <button 
        @click="centerMap"
        class="rounded-lg bg-gray-500 px-4 py-2 text-sm text-white hover:bg-gray-600"
      >
        Center Map
      </button>
    </div>

    <!-- Map Container -->
    <div class="relative">
      <div 
        ref="mapContainer" 
        class="map-container h-96 w-full rounded-lg border border-gray-300 dark:border-gray-600"
        style="height: 400px; width: 100%; position: relative; z-index: 1;"
      >
        <div v-if="loading" class="absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-gray-800/80">
          <div class="text-gray-500 dark:text-gray-400">Loading map...</div>
        </div>
      </div>

      <!-- Map Legend -->
      <div class="absolute bottom-4 left-4 rounded-lg bg-white p-3 shadow-lg dark:bg-gray-800">
        <h4 class="mb-2 text-sm font-semibold">Alert Severity</h4>
        <div class="space-y-1 text-xs">
          <div class="flex items-center gap-2">
            <div class="h-3 w-3 rounded-full bg-red-500"></div>
            <span>Critical</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="h-3 w-3 rounded-full bg-orange-500"></div>
            <span>High</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="h-3 w-3 rounded-full bg-yellow-500"></div>
            <span>Medium</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="h-3 w-3 rounded-full bg-green-500"></div>
            <span>Low</span>
          </div>
        </div>
      </div>

      <!-- Alert Count -->
      <div class="absolute top-4 right-4 rounded-lg bg-white p-2 shadow-lg dark:bg-gray-800">
        <div class="text-sm font-semibold">{{ alerts.length }} Active Alerts</div>
      </div>
    </div>

    <!-- Alert List -->
    <div v-if="alerts.length > 0" class="mt-4">
      <h3 class="mb-3 text-lg font-semibold">Recent Alerts</h3>
      <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
        <div 
          v-for="alert in alerts.slice(0, 6)" 
          :key="alert.id"
          @click="panToAlert(alert)"
          class="cursor-pointer rounded-lg border border-gray-200 p-3 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
        >
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <h4 class="font-semibold text-gray-900 dark:text-white">{{ alert.title }}</h4>
              <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ alert.description }}</p>
              <div class="mt-2 flex items-center gap-2">
                <span 
                  :class="getSeverityClass(alert.severity)"
                  class="rounded-full px-2 py-1 text-xs font-medium"
                >
                  {{ alert.severity_label }}
                </span>
                <span class="text-xs text-gray-500 dark:text-gray-400">
                  {{ alert.category_label }}
                </span>
              </div>
              <div v-if="alert.location?.address" class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                📍 {{ alert.location.address }}
              </div>
            </div>
            <div class="ml-2">
              <div 
                :class="getSeverityColor(alert.severity)"
                class="h-4 w-4 rounded-full"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- No Alerts Message -->
    <div v-else-if="!loading" class="mt-4 text-center">
      <div class="rounded-lg bg-gray-100 p-6 dark:bg-gray-800">
        <div class="text-gray-500 dark:text-gray-400">
          No alerts found for the selected criteria.
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue'
import L from 'leaflet'
import { alertMapApi } from '../api'

// Reactive data
const mapContainer = ref(null)
const map = ref(null)
const alerts = ref([])
const loading = ref(false)
const selectedSeverity = ref('')
const selectedCategory = ref('')

// Default map center (New York City)
const defaultCenter = [40.7128, -74.006]
const defaultZoom = 5 // Lower zoom to show all alerts initially

// Map markers
const markers = ref([])

// Initialize map
const initMap = async () => {
  console.log('🗺️ Initializing map...')
  await nextTick()
  
  if (!mapContainer.value) {
    console.error('❌ Map container not found')
    return
  }

  console.log('✅ Map container found:', mapContainer.value)

  // Wait a bit more to ensure DOM is ready
  setTimeout(() => {
    if (!mapContainer.value) {
      console.error('❌ Map container lost after timeout')
      return
    }

    try {
      console.log('🗺️ Creating Leaflet map...')
      console.log('🔍 Leaflet version:', L.version)
      console.log('🔍 Leaflet available:', typeof L !== 'undefined')
      
      // Fix for default markers in Leaflet (same as Next.js)
      delete L.Icon.Default.prototype._getIconUrl
      L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon-2x.png',
        iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
      })

      // Create map (following Leaflet documentation)
      map.value = L.map(mapContainer.value).setView(defaultCenter, defaultZoom)

      console.log('✅ Map created successfully')
      
      // Debug map container
      console.log('🔍 Map container dimensions:', {
        width: mapContainer.value.offsetWidth,
        height: mapContainer.value.offsetHeight,
        clientWidth: mapContainer.value.clientWidth,
        clientHeight: mapContainer.value.clientHeight
      })
      
      // Debug map instance
      console.log('🔍 Map instance:', map.value)
      console.log('🔍 Map container element:', map.value.getContainer())

      // Add text labels to the map
      setTimeout(() => {
        try {
          // Add "New York Area" label
          const nyLabel = L.marker([40.7, -74.0], {
            icon: L.divIcon({
              className: 'map-label',
              html: '<div style="background: rgba(255,255,255,0.8); padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; color: #333; border: 1px solid #ccc;">New York Area</div>',
              iconSize: [100, 20],
              iconAnchor: [50, 10]
            })
          }).addTo(map.value)
          
          // Add "Alert Zone" label
          const alertLabel = L.marker([40.6, -73.9], {
            icon: L.divIcon({
              className: 'map-label',
              html: '<div style="background: rgba(255,255,255,0.8); padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; color: #333; border: 1px solid #ccc;">Alert Zone</div>',
              iconSize: [80, 20],
              iconAnchor: [40, 10]
            })
          }).addTo(map.value)
          
          console.log('✅ Text labels added')
        } catch (error) {
          console.warn('⚠️ Failed to add labels:', error)
        }
      }, 500)

      console.log('✅ Tile layer added')

      // Force map to invalidate size and try to load tiles
      setTimeout(() => {
        if (map.value) {
          console.log('🔄 Invalidating map size')
          map.value.invalidateSize()
          
          // Try to force tile loading
          setTimeout(() => {
            if (map.value) {
              console.log('🔄 Forcing tile reload')
              map.value.eachLayer((layer) => {
                if (layer instanceof L.TileLayer) {
                  layer.redraw()
                }
              })
            }
          }, 500)
        }
      }, 100)

      // Add tile layer with CartoDB Positron (lighter background)
      const tileLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 20
      })
      
      tileLayer.on('loading', () => {
        console.log('🔄 Tiles loading...')
      })
      
      tileLayer.on('load', () => {
        console.log('✅ Tiles loaded successfully!')
      })
      
      tileLayer.on('tileerror', (e) => {
        console.error('❌ Tile error:', e)
      })
      
      tileLayer.addTo(map.value)
      
      console.log('✅ OpenStreetMap tiles added')

      // Load initial alerts
      console.log('📡 Loading alerts...')
      loadAlerts()
      
    } catch (error) {
      console.error('❌ Error creating map:', error)
    }
  }, 200)
}

// Load alerts from API
const loadAlerts = async () => {
  try {
    console.log('📡 Loading alerts from API...')
    loading.value = true
    
    const params = {
      limit: 100,
      filter: { active_only: true }
    }

    if (selectedSeverity.value) {
      params.severity = selectedSeverity.value
    }

    if (selectedCategory.value) {
      params.category = selectedCategory.value
    }

    console.log('📡 API params:', params)
    const response = await alertMapApi.getAlertsWithMap(params)
    
    console.log('📡 API response:', response.data)
    
    if (response.data.status === 'success') {
      alerts.value = response.data.data
      console.log('✅ Alerts loaded:', alerts.value.length)
      updateMapMarkers()
    } else {
      console.error('❌ API returned error:', response.data)
    }
  } catch (error) {
    console.error('❌ Failed to load alerts:', error)
    alerts.value = []
  } finally {
    loading.value = false
  }
}

// Update map markers
const updateMapMarkers = () => {
  console.log('📍 Updating map markers...')
  
  if (!map.value) {
    console.error('❌ Map not initialized')
    return
  }

  // Clear existing markers
  markers.value.forEach(marker => {
    map.value.removeLayer(marker)
  })
  markers.value = []

  console.log('📍 Adding markers for', alerts.value.length, 'alerts')

  // Collect bounds to fit all markers
  const bounds = []
  
  // Add new markers
  alerts.value.forEach((alert, index) => {
    if (alert.location?.latitude && alert.location?.longitude) {
      console.log(`📍 Adding marker ${index + 1}:`, alert.title, 'at', alert.location.latitude, alert.location.longitude)
      
      const marker = L.marker([alert.location.latitude, alert.location.longitude], {
        icon: createCustomIcon(alert.severity)
      })

      // Add popup
      marker.bindPopup(`
        <div class="p-2">
          <h3 class="font-semibold">${alert.title}</h3>
          <p class="text-sm text-gray-600">${alert.description}</p>
          <div class="mt-2 flex items-center gap-2">
            <span class="rounded-full px-2 py-1 text-xs font-medium ${getSeverityClass(alert.severity)}">
              ${alert.severity_label}
            </span>
            <span class="text-xs text-gray-500">${alert.category_label}</span>
          </div>
          ${alert.location?.address ? `<p class="mt-1 text-xs text-gray-500">📍 ${alert.location.address}</p>` : ''}
          ${alert.affected_radius ? `<p class="mt-1 text-xs text-gray-500">Radius: ${alert.affected_radius}km</p>` : ''}
        </div>
      `)

      marker.addTo(map.value)
      markers.value.push(marker)
      bounds.push([alert.location.latitude, alert.location.longitude])

      // Add affected area circle if radius is specified
      if (alert.affected_radius) {
        const circle = L.circle([alert.location.latitude, alert.location.longitude], {
          radius: alert.affected_radius * 1000, // Convert km to meters
          color: getSeverityColor(alert.severity),
          fillColor: getSeverityColor(alert.severity),
          fillOpacity: 0.1,
          weight: 2,
          opacity: 0.5,
        })
        circle.addTo(map.value)
        markers.value.push(circle)
      }
    } else {
      console.warn('⚠️ Alert missing location data:', alert.title)
    }
  })
  
  console.log('✅ Markers updated:', markers.value.length, 'total markers')
  
  // Fit map to show all markers
  if (bounds.length > 0) {
    try {
      const markerGroup = new L.featureGroup(markers.value.filter(m => m instanceof L.Marker))
      if (markerGroup.getBounds().isValid()) {
        map.value.fitBounds(markerGroup.getBounds().pad(0.1)) // Add 10% padding
        console.log('✅ Map fitted to show all markers')
      }
    } catch (error) {
      console.error('❌ Error fitting map bounds:', error)
    }
  }
}

// Create custom marker icon
const createCustomIcon = (severity) => {
  const color = getSeverityColor(severity)
  
  return L.divIcon({
    className: 'custom-marker',
    html: `
      <div style="
        background-color: ${color};
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        font-weight: bold;
      ">
        ${getSeverityIcon(severity)}
      </div>
    `,
    iconSize: [20, 20],
    iconAnchor: [10, 10],
    popupAnchor: [0, -10],
  })
}

// Get severity color
const getSeverityColor = (severity) => {
  const colors = {
    critical: '#ef4444', // red-500
    high: '#f97316',     // orange-500
    medium: '#eab308',   // yellow-500
    low: '#22c55e',      // green-500
  }
  return colors[severity] || '#6b7280' // gray-500
}

// Get severity CSS class
const getSeverityClass = (severity) => {
  const classes = {
    critical: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    high: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
    medium: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    low: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
  }
  return classes[severity] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
}

// Get severity icon
const getSeverityIcon = (severity) => {
  const icons = {
    critical: '!',
    high: '▲',
    medium: '●',
    low: '○',
  }
  return icons[severity] || '●'
}

// Pan to specific alert
const panToAlert = (alert) => {
  if (alert.location?.latitude && alert.location?.longitude) {
    map.value.setView([alert.location.latitude, alert.location.longitude], 15)
  }
}

// Center map to default position
const centerMap = () => {
  if (map.value) {
    map.value.setView(defaultCenter, defaultZoom)
  }
}

// Cleanup on unmount
onUnmounted(() => {
  if (map.value) {
    map.value.remove()
  }
})

// Initialize map when component mounts
onMounted(() => {
  initMap()
})
</script>

<style scoped>
/* Leaflet CSS fixes for Vue */
:deep(.leaflet-container) {
  height: 100%;
  width: 100%;
  border-radius: 8px;
  overflow: hidden;
}

:deep(.leaflet-map-pane) {
  z-index: 1;
}

:deep(.leaflet-tile-pane) {
  z-index: 1;
}

:deep(.leaflet-overlay-pane) {
  z-index: 2;
}

:deep(.leaflet-marker-pane) {
  z-index: 3;
}

:deep(.leaflet-popup-pane) {
  z-index: 4;
}

:deep(.leaflet-popup-content-wrapper) {
  border-radius: 8px;
}

:deep(.leaflet-popup-content) {
  margin: 0;
  padding: 0;
}

/* Custom marker styles */
:deep(.custom-marker) {
  background: transparent !important;
  border: none !important;
}

/* Map label styles */
:deep(.map-label) {
  background: transparent !important;
  border: none !important;
}

/* Fix for map container */
.alert-map-container {
  position: relative;
}

.map-container {
  position: relative;
  height: 400px;
  width: 100%;
  overflow: hidden;
  border-radius: 8px;
}
</style>
