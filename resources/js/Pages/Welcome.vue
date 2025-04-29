<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
      <header class="bg-white dark:bg-gray-800 shadow-md">
        <div class="container mx-auto px-4 py-4">
          <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
              <span class="text-emerald-600 dark:text-emerald-400">classifique</span>Fornecedores
            </h1>
            <button class="p-2 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
              </svg>
            </button>
          </div>
        </div>
      </header>
  
      <main class="container mx-auto px-4 py-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Nova Solicitação de Demanda</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição da Demanda</label>
                <select v-model="selectedDemand" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white">
                    <option value="" disabled>Selecione uma demanda</option>
                    <option v-for="demand in demands" :key="demand.id" :value="demand">{{ demand.descricao }}</option>
                </select>
                </div>
                <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CEP do Local da Demanda</label>
                <input v-model="demandLocation" maxlength="8" @blur="validarCepDemanda" required type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white" placeholder="00000000" />
                <p class="text-sm text-gray-500 mt-1">Digite somente os 8 números do CEP, sem traço.</p>
                <InputError class="mt-2" :message="demandLocationError" />
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button @click="generateSupplierList" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-md shadow-sm transition-colors">
                Gerar Lista de Fornecedores
                </button>
            </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Listas de Fornecedores Geradas</h2>
            </div>
            
            <div v-if="generatedLists.length === 0" class="text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                <line x1="3" x2="21" y1="9" y2="9"></line>
                <path d="M8 14h.01"></path>
                <path d="M12 14h.01"></path>
                <path d="M16 14h.01"></path>
                <path d="M8 18h.01"></path>
                <path d="M12 18h.01"></path>
                <path d="M16 18h.01"></path>
                </svg>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Nenhuma lista de fornecedores foi gerada ainda.</p>
            </div>

            <div v-else class="space-y-6">
                <div v-for="(list, index) in paginatedLists" :key="index" class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 flex flex-col sm:flex-row sm:justify-between sm:items-center">
                    <div>
                    <h3 class="font-medium text-gray-800 dark:text-white">{{ list.demand.descricao }}</h3>
                    <div class="flex flex-wrap gap-2 mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        CEP: {{ list.location }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 8v4l3 3"></path>
                            <circle cx="12" cy="12" r="10"></circle>
                        </svg>
                        {{ formatDate(list.createdAt) }}
                        </span>
                    </div>
                    </div>
                    <button @click="toggleListDetails(index)" class="mt-2 sm:mt-0 px-3 py-1 text-sm bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 rounded-md flex items-center transition-colors">
                    {{ list.showDetails ? 'Ocultar' : 'Detalhes' }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" :class="{ 'transform rotate-180': list.showDetails }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>
                    </button>
                </div>
                
                <div v-if="list.showDetails" class="px-4 py-3">
                    <div v-if="list.suppliers.length === 0" class="text-center py-4">
                    <p class="text-amber-600 dark:text-amber-400">Não há fornecedores habilitados para esta demanda.</p>
                    </div>
                    <div v-else>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Posição</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fornecedor</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">CEP</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Distância</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Critério</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="(supplier, supplierIndex) in list.suppliers" :key="supplierIndex" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ supplierIndex + 1 }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ supplier.name }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ supplier.cep }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ supplier.distance }} km</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                <span v-if="supplier.tiebreaker" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                Desempate (Round-Robin)
                                </span>
                                <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Menor Distância
                                </span>
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    </div>
                </div>
                </div>
            </div>

            <div v-if="generatedLists.length > itemsPerPage" class="flex justify-center items-center space-x-2 mt-4">
                <button 
                @click="changeListPage(currentListPage - 1)" 
                :disabled="currentListPage === 1"
                class="px-3 py-1 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 disabled:opacity-50"
                :class="{ 'opacity-50 cursor-not-allowed': currentListPage === 1 }"
                >
                Anterior
                </button>
                
                <div class="flex items-center space-x-1">
                <button 
                    v-for="page in totalListPages" 
                    :key="page" 
                    @click="changeListPage(page)"
                    class="w-8 h-8 flex items-center justify-center rounded-md"
                    :class="currentListPage === page ? 'bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                >
                    {{ page }}
                </button>
                </div>
                
                <button 
                @click="changeListPage(currentListPage + 1)" 
                :disabled="currentListPage === totalListPages"
                class="px-3 py-1 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 disabled:opacity-50"
                :class="{ 'opacity-50 cursor-not-allowed': currentListPage === totalListPages }"
                >
                Próxima
                </button>
            </div>
            </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Fornecedores Cadastrados</h2>
            <button @click="openSupplierModal" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-md shadow-sm transition-colors flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14M5 12h14"></path>
              </svg>
              Cadastrar Fornecedor
            </button>
          </div>
          
          <div v-if="loadingSuppliers" class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-600"></div>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Carregando fornecedores...</p>
          </div>
          
          <div v-else-if="suppliers.length === 0" class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Nenhum fornecedor cadastrado.</p>
          </div>
          
          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                  <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Razão Social</th>
                  <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">CEP</th>
                  <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Criado em</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="supplier in paginatedSuppliers" :key="supplier.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ supplier.id }}</td>
                  <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ supplier.razao_social }}</td>
                  <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ supplier.cep }}</td>
                  <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ formatarData(supplier.created_at) }}</td>
                </tr>
              </tbody>
            </table>
            
            <div v-if="suppliers.length > itemsPerPage" class="flex justify-center items-center space-x-2 mt-4">
              <button 
                @click="changeSupplierPage(currentSupplierPage - 1)" 
                :disabled="currentSupplierPage === 1"
                class="px-3 py-1 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 disabled:opacity-50"
                :class="{ 'opacity-50 cursor-not-allowed': currentSupplierPage === 1 }"
              >
                Anterior
              </button>
              
              <div class="flex items-center space-x-1">
                <button 
                  v-for="page in totalSupplierPages" 
                  :key="page" 
                  @click="changeSupplierPage(page)"
                  class="w-8 h-8 flex items-center justify-center rounded-md"
                  :class="currentSupplierPage === page ? 'bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                >
                  {{ page }}
                </button>
              </div>
              
              <button 
                @click="changeSupplierPage(currentSupplierPage + 1)" 
                :disabled="currentSupplierPage === totalSupplierPages"
                class="px-3 py-1 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 disabled:opacity-50"
                :class="{ 'opacity-50 cursor-not-allowed': currentSupplierPage === totalSupplierPages }"
              >
                Próxima
              </button>
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Demandas Cadastradas</h2>
            <button @click="openDemandModal" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-md shadow-sm transition-colors flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14M5 12h14"></path>
              </svg>
              Cadastrar Demanda
            </button>
          </div>
          
          <div v-if="loadingDemands" class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-600"></div>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Carregando demandas...</p>
          </div>
          
          <div v-else-if="demands.length === 0" class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
              <path d="M15 2H9a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"></path>
            </svg>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Nenhuma demanda cadastrada.</p>
          </div>
          
          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Descrição</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="demand in paginatedDemands" :key="demand.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ demand.descricao }}</td>
                </tr>
              </tbody>
            </table>

            <div v-if="demands.length > itemsPerPage" class="flex justify-center items-center space-x-2 mt-4">
              <button 
                @click="changeDemandPage(currentDemandPage - 1)" 
                :disabled="currentDemandPage === 1"
                class="px-3 py-1 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 disabled:opacity-50"
                :class="{ 'opacity-50 cursor-not-allowed': currentDemandPage === 1 }"
              >
                Anterior
              </button>
              
              <div class="flex items-center space-x-1">
                <button 
                  v-for="page in totalDemandPages" 
                  :key="page" 
                  @click="changeDemandPage(page)"
                  class="w-8 h-8 flex items-center justify-center rounded-md"
                  :class="currentDemandPage === page ? 'bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                >
                  {{ page }}
                </button>
              </div>
              
              <button 
                @click="changeDemandPage(currentDemandPage + 1)" 
                :disabled="currentDemandPage === totalDemandPages"
                class="px-3 py-1 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 disabled:opacity-50"
                :class="{ 'opacity-50 cursor-not-allowed': currentDemandPage === totalDemandPages }"
              >
                Próxima
              </button>
            </div>
          </div>
        </div>
  
        <div v-if="showSupplierModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Cadastrar Novo Fornecedor</h3>
                <button @click="closeSupplierModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18M6 6l12 12"></path>
                  </svg>
                </button>
              </div>
              
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Razão Social</label>
                  <input v-model="newSupplier.razao_social" maxlength="20" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white" placeholder="Razão social do Fornecedor" />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CEP</label>
                  <input v-model="newSupplier.cep" maxlength="8" required @blur="validarCep" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white" placeholder="00000000" />
                  <p class="text-sm text-gray-500 mt-1">Digite somente os 8 números do CEP, sem traço.</p>
                  <InputError class="mt-2" :message="cepError" />
                </div>
              </div>
              
              <div class="mt-6 flex justify-end">
                <button @click="closeSupplierModal" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium rounded-md shadow-sm transition-colors mr-2">
                  Cancelar
                </button>
                <button 
                  @click="addSupplier" 
                  class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-md shadow-sm transition-colors flex items-center"
                  :disabled="savingSupplier"
                >
                  <span v-if="savingSupplier" class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>
                  {{ savingSupplier ? 'Salvando...' : 'Cadastrar' }}
                </button>
              </div>
            </div>
          </div>
        </div>
  
 
        <div v-if="showDemandModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Cadastrar Nova Demanda</h3>
                <button @click="closeDemandModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18M6 6l12 12"></path>
                  </svg>
                </button>
              </div>
              
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição da Demanda</label>
                  <input v-model="newDemand.descricao" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white" placeholder="Descrição da demanda" />
                </div>
              </div>
              
              <div class="mt-6 flex justify-end">
                <button @click="closeDemandModal" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium rounded-md shadow-sm transition-colors mr-2">
                  Cancelar
                </button>
                <button 
                  @click="addDemand" 
                  class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-md shadow-sm transition-colors flex items-center"
                  :disabled="savingDemand"
                >
                  <span v-if="savingDemand" class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>
                  {{ savingDemand ? 'Salvando...' : 'Cadastrar' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </main>
  
      <footer class="bg-white dark:bg-gray-800 shadow-inner py-4">
        <div class="container mx-auto px-4 text-center text-gray-500 dark:text-gray-400 text-sm">
          &copy; {{ new Date().getFullYear() }} classifiqueFornecedores - Sistema de Gestão de Fornecedores
        </div>
      </footer>
    </div>
</template>
  
<script>
  import axios from 'axios';
  import InputError from '@/Components/InputError.vue';
  import { ref } from 'vue';
  
  export default {
    data() {
      return {
        selectedDemand: "",
        demandLocation: "",
        generatedLists: [],
        demands: [],
        suppliers: [],
        showSupplierModal: false,
        showDemandModal: false,
        newSupplier: {
            razao_social: "",
            cep: "",
        },
        newDemand: {
            descricao: ""
        },
        cepError: ref(''),
        demandLocationError: ref(''),
        itemsPerPage: 10,
        currentListPage: 1,
        currentDemandPage: 1,
        currentSupplierPage: 1,
        loadingDemands: false,
        loadingSuppliers: false,
        savingDemand: false,
        savingSupplier: false,
        error: null
      };
    },
    computed: {
      paginatedLists() {
        const start = (this.currentListPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.generatedLists.slice(start, end);
      },

      totalListPages() {
        return Math.ceil(this.generatedLists.length / this.itemsPerPage);
      },
      
      paginatedDemands() {
        const start = (this.currentDemandPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.demands.slice(start, end);
      },
      totalDemandPages() {
        return Math.ceil(this.demands.length / this.itemsPerPage);
      },
      

      paginatedSuppliers() {
        const start = (this.currentSupplierPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.suppliers.slice(start, end);
      },
      totalSupplierPages() {
        return Math.ceil(this.suppliers.length / this.itemsPerPage);
      }
    },

    created() {
      this.fetchDemands();
      this.fetchSuppliers();
    },

    methods: {
      async fetchDemands() {
        this.loadingDemands = true;
        this.error = null;
        
        try {
          const response = await axios.get('api/demandas');
          this.demands = response.data;

          if (this.demands.length > 0 && !this.newSupplier.demandId) {
            this.newSupplier.demandId = this.demands[0].id;
          }
        } catch (error) {
          console.error('Erro ao buscar demandas:', error);
        } finally {
          this.loadingDemands = false;
        }
      },
      
      async fetchSuppliers() {
        this.loadingSuppliers = true;
        this.error = null;
        
        try {
          const response = await axios.get('api/fornecedores');
          this.suppliers = response.data;
        } catch (error) {
          console.error('Erro ao buscar fornecedores:', error);
          this.error = 'Não foi possível carregar os fornecedores. Por favor, tente novamente.';
          this.suppliers = [];
        } finally {
          this.loadingSuppliers = false;
        }
      },
      
      async addSupplier() {
        if (!this.validarCep()) return;

        if (!this.newSupplier.razao_social || !this.newSupplier.cep) {
          alert("Por favor, preencha todos os campos.");
          return;
        }
        
        this.savingSupplier = true;
        
        try {
          await axios.post('api/fornecedores', this.newSupplier);
          
          await this.fetchSuppliers();
 
          this.closeSupplierModal();
        } catch (error) {
          console.error('Erro ao cadastrar fornecedor:', error);
          alert('Não foi possível cadastrar o fornecedor. Por favor, tente novamente.');
        } finally {
          this.savingSupplier = false;
        }
      },
      
      async addDemand() {
        if (!this.newDemand.descricao) {
          alert("Por favor, informe a descrição da demanda.");
          return;
        }
        
        this.savingDemand = true;
        
        try {
          await axios.post('api/demandas', this.newDemand);

          await this.fetchDemands();

          this.closeDemandModal();
        } catch (error) {
          console.error('Erro ao cadastrar demanda:', error);
          alert('Não foi possível cadastrar a demanda. Por favor, tente novamente.');
        } finally {
          this.savingDemand = false;
        }
      },

        validarCep()  {
            if (this.newSupplier.cep) {
                this.newSupplier.cep = this.newSupplier.cep.replace(/\D/g, '');
                
                if (this.newSupplier.cep.length !== 8) {
                this.cepError = 'O CEP deve conter 8 números';
                return false;
                }
                
                this.cepError = '';
                return true;
            }

            this.cepError = 'O CEP é obrigatório';
            return false;
        },
        validarCepDemanda()  {
            if (this.demandLocation) {
                this.demandLocation = this.demandLocation.replace(/\D/g, '');
                
                if (this.demandLocation.length !== 8) {
                this.demandLocationError = 'O CEP deve conter 8 números';
                return false;
                }
                
                this.demandLocationError = '';
                return true;
            }

            this.demandLocationError = 'O CEP é obrigatório';
            return false;
        },
        formatarData (isoString) {
            const data = new Date(isoString);
            return new Intl.DateTimeFormat('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            }).format(data);
        },
      getDemandName(demandId) {
        const demand = this.demands.find(d => d.id === demandId);
        return demand ? demand.description : 'Não associado';
      },
      
      getSupplierCountForDemand(demandId) {
        return this.suppliers.filter(s => s.demandId === demandId).length;
      },
      
    async generateSupplierList() {
        if (!this.selectedDemand || !this.demandLocation) {
            alert("Por favor, selecione uma demanda e informe o CEP do local.");
            return;
        }
        
        if (!this.validarCepDemanda()) {
            return;
        }
        
        try {
            const loadingToast = this.$toast ? this.$toast.show('Buscando fornecedores próximos...') : null;

            const response = await axios.post(`api/demandas/${this.selectedDemand.id}/solicitar`, {
            cep_execucao: this.demandLocation
            });
            
            const responseData = response.data;

            if (loadingToast && this.$toast) {
            this.$toast.hide(loadingToast);
            }
            
            if (responseData.message && responseData.message.includes('Não há fornecedores')) {
            alert(responseData.message);
            return;
            }

            const suppliers = responseData.fornecedores_ordenados.map(item => {
            return {
                id: item.fornecedor.id,
                name: item.fornecedor.razao_social,
                cep: item.fornecedor.cep,
                distance: parseFloat(item.distancia).toFixed(1),
                tiebreaker: item.round_robin_index > 0
            };
            });
            
            const newList = {
            demand: this.selectedDemand,
            location: this.demandLocation,
            createdAt: new Date(),
            suppliers,
            showDetails: true
            };

            this.generatedLists.unshift(newList);
            this.currentListPage = 1;

            this.selectedDemand = "";
            this.demandLocation = "";
            
        } catch (error) {
            console.error('Erro ao gerar lista de fornecedores:', error);
            if (error.response && error.response.data && error.response.data.message) {
            alert(error.response.data.message);
            } else {
            alert('Não foi possível gerar a lista de fornecedores. Por favor, tente novamente.');
            }
        }
    },
  
      calculateMockDistance(supplierCep, demandCep) {
        const supplierNum = parseInt(supplierCep.replace(/\D/g, ''));
        const demandNum = parseInt(demandCep.replace(/\D/g, ''));

        let distance = Math.abs(supplierNum - demandNum) % 50;

        return Math.round(distance * 10) / 10;
      },
  
      applyRoundRobin(suppliers) {
        const distanceGroups = {};
        suppliers.forEach(supplier => {
          if (!distanceGroups[supplier.distance]) {
            distanceGroups[supplier.distance] = [];
          }
          distanceGroups[supplier.distance].push(supplier);
        });

        Object.keys(distanceGroups).forEach(distance => {
          const group = distanceGroups[distance];
          if (group.length > 1) {
            for (let i = 1; i < group.length; i++) {
              group[i].tiebreaker = true;
            }

            this.shuffleArray(group);
          }
        });

        let index = 0;
        Object.keys(distanceGroups).sort((a, b) => a - b).forEach(distance => {
          distanceGroups[distance].forEach(supplier => {
            suppliers[index++] = supplier;
          });
        });
      },
  
      shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
          const j = Math.floor(Math.random() * (i + 1));
          [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
      },
  
      toggleListDetails(index) {
        const actualIndex = (this.currentListPage - 1) * this.itemsPerPage + index;
        this.generatedLists[actualIndex].showDetails = !this.generatedLists[actualIndex].showDetails;
      },
  
      formatDate(date) {
        return new Intl.DateTimeFormat('pt-BR', {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        }).format(date);
      },
  
      openSupplierModal() {
        this.showSupplierModal = true;
        this.newSupplier = { 
          name: "", 
          cep: "",
          demandId: this.demands.length > 0 ? this.demands[0].id : null
        };
      },
  
      closeSupplierModal() {
        this.showSupplierModal = false;
      },
  
      openDemandModal() {
        this.showDemandModal = true;
        this.newDemand = { descricao: "" };
      },
  
      closeDemandModal() {
        this.showDemandModal = false;
      },
  
      changeDemandPage(page) {
        if (page > 0 && page <= this.totalDemandPages) {
          this.currentDemandPage = page;
        }
      },
  
      changeListPage(page) {
        if (page > 0 && page <= this.totalListPages) {
          this.currentListPage = page;
        }
      },
      
      changeSupplierPage(page) {
        if (page > 0 && page <= this.totalSupplierPages) {
          this.currentSupplierPage = page;
        }
      }
    }
  };
</script>