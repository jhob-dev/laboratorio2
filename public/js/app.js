/**
 * LABCLÍNICO - Aplicación de Exámenes Clínicos
 * JavaScript para modales, cálculo de edad, pagos dinámicos y más
 */
(function () {
    'use strict';

    // ============================================
    // CÁLCULO DE EDAD EN TIEMPO REAL
    // ============================================
    window.calcularEdad = function () {
        const fechaInput = document.getElementById('fecha_nacimiento');
        const edadInput = document.getElementById('edad');

        if (!fechaInput || !edadInput) return;

        const fechaNacimiento = fechaInput.value;

        if (!fechaNacimiento) {
            edadInput.value = '';
            return;
        }

        const nacimiento = new Date(fechaNacimiento);
        const hoy = new Date();
        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const mes = hoy.getMonth() - nacimiento.getMonth();

        if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
            edad--;
        }

        edadInput.value = edad + ' años';

        // También calcular vía API como respaldo
        fetch(`${APP_URL}pacientes/calcular-edad?fecha=${fechaNacimiento}`)
            .then(response => response.json())
            .then(data => {
                if (data.edad !== undefined) {
                    edadInput.value = data.edad + ' años';
                }
            })
            .catch(() => { });
    };

    // ============================================
    // SIDEBAR TOGGLE (Móvil)
    // ============================================
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });

        // Cerrar sidebar al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }

    // ============================================
    // MODAL: REALIZAR EXAMEN
    // ============================================
    // document.addEventListener('click', function (e) {
    //     const btnRealizar = e.target.closest('.btn-realizar-examen');
    //     if (!btnRealizar) return;

    //     const pacienteId = btnRealizar.dataset.pacienteId;
    //     const pacienteNombre = btnRealizar.dataset.pacienteNombre;

    //     abrirModalRealizarExamen(pacienteId, pacienteNombre);
    // });
    // Evento para el botón de Exámenes Rápidos
    const btnExamenesRapidos = document.getElementById('btnExamenesRapidos');
    if (btnExamenesRapidos) {
        btnExamenesRapidos.addEventListener('click', function (e) {
            e.preventDefault();
            // Redirige a la lista de pacientes para seleccionar uno rápidamente
            window.location.href = APP_URL + 'pacientes';
        });
    }

    function abrirModalRealizarExamen(pacienteId, pacienteNombre) {
        // Obtener lista de exámenes vía API
        fetch(`${APP_URL}examenes/listar`)
            .then(response => response.json())
            .then(data => {
                const examenes = data.examenes;
                construirModalExamen(pacienteId, pacienteNombre, examenes);
            })
            .catch(error => {
                console.error('Error al cargar exámenes:', error);
                alert('Error al cargar la lista de exámenes. Intente nuevamente.');
            });
    }

    function construirModalExamen(pacienteId, pacienteNombre, examenes) {
        let examenesHTML = '';

        for (const [categoria, lista] of Object.entries(examenes)) {
            examenesHTML += `
                <div class="exam-category">
                    <h4>${categoria}</h4>
                    <div class="exam-list">
                        ${lista.map(examen => `
                            <div class="exam-item" data-examen-id="${examen.id}" data-examen-nombre="${examen.nombre}">
                                <div class="exam-info">
                                    <div class="exam-name">${examen.nombre}</div>
                                    <div class="exam-code">Código: ${examen.codigo}</div>
                                </div>
                                <div class="exam-price">$${parseFloat(examen.precio).toFixed(2)}</div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        const modalHTML = `
            <div class="modal-overlay" id="modalRealizarExamen">
                <div class="modal modal-lg">
                    <div class="modal-header">
                        <h3>
                            <i class="fas fa-vial"></i>
                            Realizar Examen - ${pacienteNombre}
                        </h3>
                        <button class="modal-close" onclick="cerrarModal('modalRealizarExamen')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Selección de examen -->
                        <h4 style="margin-bottom: 15px;">Seleccione el examen a realizar:</h4>
                        <div class="exam-categories-container">
                            ${examenesHTML}
                        </div>

                        <input type="hidden" id="selectedExamenId" value="">
                        <input type="hidden" id="selectedExamenNombre" value="">

                        <!-- Método de Pago -->
                        <h4 style="margin-top: 25px; margin-bottom: 10px;">Método de Pago:</h4>
                        <div class="payment-methods">
                            <div class="payment-method-option">
                                <input type="radio" name="metodo_pago" value="Efectivo" id="pago_efectivo" checked>
                                <label for="pago_efectivo">
                                    <i class="fas fa-money-bill-wave"></i>
                                    Efectivo
                                </label>
                            </div>
                            <div class="payment-method-option">
                                <input type="radio" name="metodo_pago" value="Pago Móvil" id="pago_movil">
                                <label for="pago_movil">
                                    <i class="fas fa-mobile-alt"></i>
                                    Pago Móvil
                                </label>
                            </div>
                            <div class="payment-method-option">
                                <input type="radio" name="metodo_pago" value="Transferencia" id="pago_transferencia">
                                <label for="pago_transferencia">
                                    <i class="fas fa-exchange-alt"></i>
                                    Transferencia
                                </label>
                            </div>
                            <div class="payment-method-option">
                                <input type="radio" name="metodo_pago" value="Punto de Venta" id="pago_punto">
                                <label for="pago_punto">
                                    <i class="fas fa-credit-card"></i>
                                    Punto de Venta
                                </label>
                            </div>
                        </div>

                        <!-- Campo para referencia de pago (dinámico) -->
                        <div class="payment-reference-field" id="referenciaField">
                            <label for="referencia_pago">
                                <i class="fas fa-hashtag"></i>
                                Últimos 4 dígitos de la transacción <span class="required">*</span>
                            </label>
                            <input type="text" id="referencia_pago" class="form-control"
                                   placeholder="Ingrese los últimos 4 dígitos"
                                   maxlength="4" pattern="\\d{4}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="cerrarModal('modalRealizarExamen')">
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-success" id="btnConfirmarExamen">
                            <i class="fas fa-check"></i> Confirmar y Realizar Examen
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('modalContainer').innerHTML = modalHTML;

        // Event Listeners dentro del modal
        setupModalExamenListeners(pacienteId);
    }

    function setupModalExamenListeners(pacienteId) {
        // Selección de examen
        document.querySelectorAll('.exam-item').forEach(item => {
            item.addEventListener('click', function () {
                document.querySelectorAll('.exam-item').forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('selectedExamenId').value = this.dataset.examenId;
                document.getElementById('selectedExamenNombre').value = this.dataset.examenNombre;
            });
        });

        // Cambio de método de pago
        const radiosPago = document.querySelectorAll('input[name="metodo_pago"]');
        const referenciaField = document.getElementById('referenciaField');

        radiosPago.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value === 'Pago Móvil' || this.value === 'Transferencia') {
                    referenciaField.classList.add('visible');
                    document.getElementById('referencia_pago').setAttribute('required', 'required');
                } else {
                    referenciaField.classList.remove('visible');
                    document.getElementById('referencia_pago').removeAttribute('required');
                }
            });
        });

        // Confirmar examen
        document.getElementById('btnConfirmarExamen').addEventListener('click', function () {
            const examenId = document.getElementById('selectedExamenId').value;
            const metodoPago = document.querySelector('input[name="metodo_pago"]:checked').value;
            const referenciaPago = document.getElementById('referencia_pago').value;

            if (!examenId) {
                alert('Por favor, seleccione un examen.');
                return;
            }

            if ((metodoPago === 'Pago Móvil' || metodoPago === 'Transferencia') && referenciaPago.length < 4) {
                alert('Debe ingresar los últimos 4 dígitos de la transacción.');
                return;
            }

            // Enviar al servidor
            const formData = new FormData();
            formData.append('paciente_id', pacienteId);
            formData.append('examen_id', examenId);
            formData.append('metodo_pago', metodoPago);
            formData.append('referencia_pago', referenciaPago);

            fetch(`${APP_URL}examenes/realizar`, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cerrarModal('modalRealizarExamen');
                        alert(data.mensaje);
                        // Recargar la página para ver el nuevo examen
                        location.reload();
                    } else {
                        alert('Error: ' + (data.error || 'No se pudo procesar la solicitud.'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error de conexión. Intente nuevamente.');
                });
        });
    }

    // ============================================
    // MODAL: AGREGAR RESULTADOS
    // ============================================
    document.addEventListener('click', function (e) {
        const btnResultados = e.target.closest('.btn-agregar-resultados');
        if (!btnResultados) return;

        const ordenId = btnResultados.dataset.ordenId;
        abrirModalResultados(ordenId);
    });

    function abrirModalResultados(ordenId) {
        fetch(`${APP_URL}examenes/obtener?orden_id=${ordenId}`)
            .then(response => response.json())
            .then(data => {
                const examen = data.examen;
                construirModalResultados(ordenId, examen);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los datos del examen.');
            });
    }

    function construirModalResultados(ordenId, examen) {
        const modalHTML = `
            <div class="modal-overlay" id="modalResultados">
                <div class="modal modal-lg">
                    <div class="modal-header">
                        <h3>
                            <i class="fas fa-edit"></i>
                            Agregar Resultados
                        </h3>
                        <button class="modal-close" onclick="cerrarModal('modalResultados')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="info-grid" style="margin-bottom: 20px;">
                            <div class="info-item">
                                <span class="info-label">Paciente:</span>
                                <span class="info-value">${examen.nombre_completo}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Examen:</span>
                                <span class="info-value">${examen.nombre_examen} (${examen.codigo})</span>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label for="resultado_texto">
                                <i class="fas fa-clipboard-list"></i>
                                Resultados del Examen <span class="required">*</span>
                            </label>
                            <textarea id="resultado_texto" class="form-control"
                                      rows="8" placeholder="Ingrese los resultados del examen..."
                                      required>${examen.resultado_texto || ''}</textarea>
                        </div>

                        <div class="form-group full-width" style="margin-top: 15px;">
                            <label for="observaciones">
                                <i class="fas fa-comment"></i>
                                Observaciones
                            </label>
                            <textarea id="observaciones" class="form-control"
                                      rows="3" placeholder="Observaciones adicionales...">${examen.observaciones || ''}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="cerrarModal('modalResultados')">
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-success" id="btnGuardarResultados">
                            <i class="fas fa-save"></i> Guardar Resultados
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('modalContainer').innerHTML = modalHTML;

        document.getElementById('btnGuardarResultados').addEventListener('click', function () {
            const resultadoTexto = document.getElementById('resultado_texto').value;
            const observaciones = document.getElementById('observaciones').value;

            if (!resultadoTexto.trim()) {
                alert('Debe ingresar los resultados del examen.');
                return;
            }

            const formData = new FormData();
            formData.append('orden_id', ordenId);
            formData.append('resultado_texto', resultadoTexto);
            formData.append('observaciones', observaciones);

            fetch(`${APP_URL}examenes/guardar-resultados`, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cerrarModal('modalResultados');
                        alert(data.mensaje);
                        location.reload();
                    } else {
                        alert('Error: ' + (data.error || 'No se pudieron guardar los resultados.'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error de conexión.');
                });
        });
    }

    // ============================================
    // FUNCIONES GLOBALES DE MODAL
    // ============================================
    window.cerrarModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.remove();
        }
        // También limpiar el contenedor
        document.getElementById('modalContainer').innerHTML = '';
    };

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const modales = document.querySelectorAll('.modal-overlay');
            modales.forEach(modal => modal.remove());
            document.getElementById('modalContainer').innerHTML = '';
        }
    });

    // Cerrar modal al hacer clic fuera del contenido
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.remove();
        }
    });

    // Evento para el botón de Exámenes Rápidos
    const btnExamenesRapidos = document.getElementById('btnExamenesRapidos');
    if (btnExamenesRapidos) {
        btnExamenesRapidos.addEventListener('click', function (e) {
            e.preventDefault();
            // Redirige a la lista de pacientes para seleccionar uno rápidamente
            window.location.href = APP_URL + 'pacientes';
        });
    }

})();