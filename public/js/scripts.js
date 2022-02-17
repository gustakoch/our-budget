document.addEventListener('DOMContentLoaded', function () {
    const formNewRecipe = document.querySelector('#form-new-recipe')
    const formEditRecipe = document.querySelector('#form-edit-recipe')
    const formNewExpense = document.querySelector('#form-new-expense')
    const formEditExpense = document.querySelector('#form-edit-expense')
    const formExpenseCancellation = document.querySelector('#form-expense-cancellation')
    const formExpenseCancellations = document.querySelector('#form-expense-cancellation-all')
    const formNewUser = document.querySelector('#form-new-user')
    const formEditUser = document.querySelector('#form-edit-user')

    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    window.onload = function () {
        let reloadingRecipe = sessionStorage.getItem('reloadingRecipe');
        let reloadingExpense = sessionStorage.getItem('reloadingExpense');

        if (reloadingRecipe) {
            sessionStorage.removeItem('reloading');
            return jQuery('#addRecipeModal').modal('show')
        }

        if (reloadingExpense) {
            sessionStorage.removeItem('reloading');
            return jQuery('#addExpenseModal').modal('show')
        }

        sessionStorage.removeItem('realizedAmount')
    }

    function getAppUrl() {
        let appUrl

        jQuery.ajax({
            url: 'app/url',
            type: 'get',
            dataType: 'json',
            timeout: 20000,
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (response) {
                appUrl = response.appUrl
            }
        })

        return appUrl
    }

    function amountParseFloat(amount) {
        return parseFloat(amount).toLocaleString('pt-br', {
            minimumFractionDigits: 2, maximumFractionDigits: 2
        })
    }

    function showLoadingOnButton(button, newText) {
        jQuery(button).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            ${newText}
        `)
        jQuery(button).attr('disabled', true)
    }

    function stopLoadingOnButton(button, newText) {
        jQuery(button).html(newText)
        jQuery(button).attr('disabled', false)
    }

    function swalNotification(title, text, icon, confirmButtonText) {
        return Swal.fire({ title, text, icon, confirmButtonColor: '#3085d6', confirmButtonText })
    }

    jQuery(document).on('click', '.save-recipe', function (e) {
        e.preventDefault()

        let button = jQuery(this)

        let description = jQuery('input[name="description"]').val()
        let category = Number(jQuery('select[name="category"]').val())
        let amount = jQuery('input[name="budgeted_amount"]').val()

        if (!description || !category || !amount) {
            swalNotification('Presta atenção aí', 'Os dados obrigatórios devem ser preenchidos.', 'error', 'Tá, entendi')
            return false
        }

        if (amount == '0') {
            swalNotification('Oops...', 'O valor informado deve ser maior do que zero.', 'error', 'Tentar novamente')
            return false
        }

        jQuery.ajax({
            url: 'recipes/store',
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: jQuery('#form-new-recipe').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                showLoadingOnButton(button, 'Carregando...')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                stopLoadingOnButton(button, 'Salvar e fechar')
            },
            success: function (response) {
                stopLoadingOnButton(button, 'Salvar e fechar')

                if (response.ok) {
                    jQuery('#addRecipeModal').modal('hide')
                    location.reload()

                    sessionStorage.removeItem('reloadingRecipe')
                }
            }
        })
    })

    jQuery(document).on('click', '.save-recipe-continue', function (e) {
        e.preventDefault()

        let button = jQuery(this)

        let description = jQuery('input[name="description"]').val()
        let category = Number(jQuery('select[name="category"]').val())
        let amount = jQuery('input[name="budgeted_amount"]').val()

        if (!description || !category || !amount) {
            swalNotification('Presta atenção aí', 'Os dados obrigatórios devem ser preenchidos.', 'error', 'Tá, entendi')
            return false
        }

        if (amount == '0') {
            swalNotification('Oops...', 'O valor informado deve ser maior do que zero.', 'error', 'Tentar novamente')
            return false
        }

        jQuery.ajax({
            url: 'recipes/store',
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: jQuery('#form-new-recipe').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                showLoadingOnButton(button, 'Carregando...')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                stopLoadingOnButton(button, 'Salvar e continuar')
            },
            success: function (response) {
                stopLoadingOnButton(button, 'Salvar e continuar')

                if (response.ok) {
                    jQuery('#addRecipeModal').modal('hide')

                    sessionStorage.setItem('reloadingRecipe', 'true');
                    document.location.reload()
                }
            }
        })
    })

    jQuery(document).on('click', '.save-expense-continue', function (e) {
        e.preventDefault()

        let button = jQuery(this)

        let description = jQuery('input[name="description_expense"]').val()
        let category = Number(jQuery('select[name="category_expense"]').val())
        let amount = jQuery('input[name="budgeted_amount_expense"]').val()

        if (!description || !category || !amount) {
            swalNotification('Presta atenção aí', 'Os dados obrigatórios devem ser preenchidos.', 'error', 'Tá, entendi')
            return false
        }

        if (amount == '0') {
            swalNotification('Oops...', 'O valor informado deve ser maior do que zero.', 'error', 'Tentar novamente')
            return false
        }

        let isWithCreditCard = jQuery('#was_with_credit_card').is(':checked')

        if (isWithCreditCard) {
            let creditCard = Number(jQuery('select[name="credit_card"]').val())

            if (!creditCard) {
                swalNotification('Cartão de crédito', 'Por favor, selecione o cartão de crédito para vincular na despesa.', 'error', 'Tá, entendi')
                return false
            }
        }

        jQuery.ajax({
            url: 'expenses/store',
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: jQuery('#form-new-expense').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                showLoadingOnButton(button, 'Carregando...')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                stopLoadingOnButton(button, 'Salvar e continuar')
            },
            success: function (response) {
                stopLoadingOnButton(button, 'Salvar e continuar')

                if (response.ok) {
                    jQuery('#addExpenseModal').modal('hide')

                    sessionStorage.setItem('reloadingExpense', 'true');
                    document.location.reload();
                }
            }
        })
    })

    jQuery(document).on('click', '.save-category', function (e) {
        e.preventDefault()

        let button = jQuery(this)

        let description = jQuery('input[name="description_category"]').val()
        let belongsTo = Number(jQuery('select[name="belongs_to"]').val())
        let color = jQuery('input[name="color"]').val()

        if (!description || !belongsTo || !color) {
            swalNotification('Presta atenção aí', 'Os dados obrigatórios devem ser preenchidos.', 'error', 'Tá, entendi')
            return false
        }

        jQuery.ajax({
            url: 'categories/store',
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: jQuery('#form-new-category').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                showLoadingOnButton(button, 'Carregando...')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                stopLoadingOnButton(button, 'Salvar e fechar')
            },
            success: function (response) {
                stopLoadingOnButton(button, 'Salvar e fechar')

                if (response.ok) {
                    jQuery('#addCategoryModal').modal('hide')
                    location.reload()
                }
            }
        })
    })

    jQuery(document).on('click', '.save-card', function (e) {
        e.preventDefault()

        let button = jQuery(this)

        let description = jQuery('input[name="card_description"]').val()
        let invoiceDay = jQuery('input[name="invoice_day"]').val()

        if (!description || !invoiceDay) {
            swalNotification('Presta atenção aí', 'Por favor, precisamos de uma descrição e o dia do vencimento da fatura.', 'error', 'Tá, entendi')
            return false
        }

        if (invoiceDay <= 0 || invoiceDay > 31) {
            swalNotification('Olha só...', 'Você precisa informar algum valor entre 1 e 31, blza?', 'error', 'Tá, entendi')
            return false
        }

        jQuery.ajax({
            url: 'cards/store',
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: jQuery('#form-new-card').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                showLoadingOnButton(button, 'Carregando...')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                stopLoadingOnButton(button, 'Salvar cartão')
            },
            success: function (response) {
                stopLoadingOnButton(button, 'Salvar cartão')

                if (response.ok) {
                    jQuery('#addCardModal').modal('hide')
                    location.reload()
                } else {
                    swalNotification('Oops...', response.message, 'error', 'Tá, entendi')
                    return false
                }
            }
        })
    })

    jQuery(document).on('click', '#first-access', function (e) {
        e.preventDefault()

        let button = jQuery(this)

        let name = jQuery('input[name="name"]').val()
        let email = jQuery('input[name="email"]').val()
        let password = jQuery('input[name="password"]').val()
        let passwordConfirmation = jQuery('input[name="password_confirmation"]').val()

        if (!name || !email || !password || !passwordConfirmation) {
            swalNotification('Presta atenção aí', 'Os dados obrigatórios devem ser preenchidos.', 'error', 'Tá, entendi')
            return false
        }

        if (password != passwordConfirmation) {
            swalNotification('Presta atenção aí', 'As novas senhas não conferem! Por favor, verifique os dados e tente novamente.', 'error', 'Tá, entendi')
            return false
        }

        jQuery.ajax({
            url: 'first-access',
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: jQuery('#form-first-access').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                showLoadingOnButton(button, 'Carregando...')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                stopLoadingOnButton(button, 'Salvar dados')
            },
            success: function (response) {
                stopLoadingOnButton(button, 'Salvar dados')

                let appUrl = getAppUrl()

                window.location.href = `${appUrl}/dashboard`
            }
        })
    })

    jQuery(document).on('change', 'select[name="installments_expense"]', function (e) {
        jQuery('input[name="repeat_next_months_expense"]').prop('checked', false)

        if (jQuery(this).val() == '1') {
            jQuery('input[name="budgeted_amount_expense"]').attr('placeholder', 'Informe o valor orçado (R$)')
            jQuery('input[name="repeat_next_months_expense"]').attr('disabled', false)

            return false
        }

        jQuery('input[name="budgeted_amount_expense"]').attr('placeholder', 'Informe o valor da parcela (R$)')
        jQuery('input[name="repeat_next_months_expense"]').attr('disabled', true)
    })

    jQuery(document).on('click', '.save-expense', function (e) {
        e.preventDefault()

        let button = jQuery(this)

        let description = jQuery('input[name="description_expense"]').val()
        let category = Number(jQuery('select[name="category_expense"]').val())
        let amount = jQuery('input[name="budgeted_amount_expense"]').val()

        if (!description || !category || !amount) {
            swalNotification('Presta atenção aí', 'Os dados obrigatórios devem ser preenchidos.', 'error', 'Tá, entendi')
            return false
        }

        if (amount == '0') {
            swalNotification('Oops...', 'O valor informado deve ser maior do que zero.', 'error', 'Tentar novamente')
            return false
        }

        let isWithCreditCard = jQuery('#was_with_credit_card').is(':checked')

        if (isWithCreditCard) {
            let creditCard = Number(jQuery('select[name="credit_card"]').val())

            if (!creditCard) {
                swalNotification('Cartão de crédito', 'Por favor, selecione o cartão de crédito para vincular a despesa.', 'error', 'Tá, entendi')
                return false
            }
        }

        jQuery.ajax({
            url: 'expenses/store',
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: jQuery('#form-new-expense').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                showLoadingOnButton(button, 'Carregando...')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                stopLoadingOnButton(button, 'Salvar e fechar')
            },
            success: function (response) {
                stopLoadingOnButton(button, 'Salvar e fechar')

                if (response.ok) {
                    jQuery('#addExpenseModal').modal('hide')
                    location.reload()

                    sessionStorage.removeItem('reloadingExpense')
                }
            }
        })
    })

    jQuery(document).on('click', '.update-recipe', function (e) {
        e.preventDefault()

        let button = jQuery(this)

        let description = jQuery('input[name="description_edit"]').val()
        let amount = jQuery('input[name="budgeted_amount_edit"]').val()

        if (!description || !amount) {
            swalNotification('Presta atenção aí', 'Os dados obrigatórios devem estar preenchidos.', 'error', 'Tá, entendi')
            return false
        }

        if (amount == '0' || amount == 0) {
            swalNotification('Oops...', 'O valor orçado deve ser maior do que zero.', 'error', 'Tentar novamente')
            return false
        }

        jQuery.ajax({
            url: 'recipes/update',
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: jQuery('#form-edit-recipe').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                showLoadingOnButton(button, 'Carregando...')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                stopLoadingOnButton(button, 'Atualizar receita')
            },
            success: function (response) {
                stopLoadingOnButton(button, 'Atualizar receita')

                if (response.ok) {
                    jQuery('#editRecipeModal').modal('hide')
                    location.reload()
                }
            }
        })
    })

    jQuery(document).on('click', '.update-category', function (e) {
        e.preventDefault()

        let button = jQuery(this)

        let description = jQuery('input[name="description_category_edit"]').val()
        let belongsTo = Number(jQuery('select[name="belongs_to_edit"]').val())

        if (!description || !belongsTo) {
            swalNotification('Presta atenção aí', 'Os dados obrigatórios devem estar preenchidos.', 'error', 'Tá, entendi')
            return false
        }

        jQuery.ajax({
            url: 'categories/update',
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: jQuery('#form-edit-category').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                showLoadingOnButton(button, 'Carregando...')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                stopLoadingOnButton(button, 'Atualizar categoria')
            },
            success: function (response) {
                stopLoadingOnButton(button, 'Atualizar categoria')
                jQuery('#loadingSpinner').show()

                if (response.ok) {
                    jQuery('#editCategoryModal').modal('hide')
                    location.reload()
                }
            }
        })
    })

    jQuery(document).on('click', '.update-card', function (e) {
        e.preventDefault()

        let button = jQuery(this)

        let description = jQuery('input[name="card_description_edit"]').val()
        let invoiceDay = jQuery('input[name="invoice_day"]').val()

        if (!description || !invoiceDay) {
            swalNotification('Presta atenção aí', 'Por favor, precisamos de uma descrição e o dia do vencimento da fatura.', 'error', 'Tá, entendi')
            return false
        }

        jQuery.ajax({
            url: 'cards/update',
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: jQuery('#form-edit-card').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                showLoadingOnButton(button, 'Carregando...')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                stopLoadingOnButton(button, 'Atualizar cartão')
            },
            success: function (response) {
                stopLoadingOnButton(button, 'Atualizar cartão')

                if (response.ok) {
                    jQuery('#editCardModal').modal('hide')
                    location.reload()
                }
            }
        })
    })

    jQuery(document).on('click', '.update-expense', function (e) {
        e.preventDefault()

        let button = jQuery(this)

        let description = jQuery('input[name="description_expense_edit"]').val()
        let period = Number(jQuery('input[name="period_expense_edit"]').val())
        let budgetedAmount = Number(jQuery('input[name="budgeted_amount_expense_edit"]').val())
        let realizedAmount = jQuery('input[name="realized_amount_expense_edit"]').val()
        let totalRealizedAmount = Number(jQuery('input[name="total_realized_amount"]').val())
        let creditCard = Number(jQuery('select[name="credit_card_edit"]').val())
        let isWithCreditCard = jQuery('#was_with_credit_card_edit').is(':checked')
        let idExpense = jQuery('input[name="id_expense"]').val()
        let categoryActive = jQuery('input[name="category_active"]').val()
        let categoryExpense = jQuery('select[name="category_expense_edit"]').val()

        if (!description || !budgetedAmount) {
            swalNotification('Presta atenção aí', 'Os dados obrigatórios devem estar preenchidos.', 'error', 'Tá, entendi')
            return false
        }

        if (budgetedAmount == 0 || realizedAmount === '0') {
            swalNotification('Oops...', 'Por favor, informe valores superiores a zero.', 'error', 'Tá, entendi')
            return false
        }

        realizedAmount = Number(realizedAmount)

        if (realizedAmount > budgetedAmount) {
            swalNotification('Oops...', 'Operação proibida! Você não deve lançar um valor superior ao orçado.', 'error', 'Tá, entendi')
            return false
        }

        if ((totalRealizedAmount + realizedAmount) > budgetedAmount) {
            swalNotification('Oops...', 'Operação proibida! O total de valores lançados é superior ao valor orçado.', 'error', 'Tá, entendi')
            return false
        }

        if (isWithCreditCard) {
            if (!creditCard) {
                swalNotification('Cartão de crédito', 'Por favor, selecione o cartão de crédito para vincular na despesa.', 'error', 'Tá, entendi')
                return false
            }
        }

        jQuery.ajax({
            url: 'expenses/verify/payment',
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: {
                description_expense_edit: description,
                budgeted_amount_expense_edit: budgetedAmount,
                realized_amount_expense_edit: realizedAmount,
                credit_card_edit: creditCard,
                period_expense_edit: period,
                id_expense: idExpense
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
            },
            success: function (response) {
                if (response.ok) {
                    jQuery.ajax({
                        url: 'expenses/update',
                        type: 'post',
                        dataType: 'json',
                        timeout: 20000,
                        data: {
                            description_expense_edit: description,
                            budgeted_amount_expense_edit: budgetedAmount,
                            realized_amount_expense_edit: realizedAmount,
                            credit_card_edit: creditCard,
                            period_expense_edit: period,
                            id_expense: idExpense,
                            category_active: categoryActive,
                            category_expense_edit: categoryExpense,
                            payment_method_change: 'N' // Payment method was not changed
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        beforeSend: function () {
                            showLoadingOnButton(button, 'Carregando...')
                        },
                        error: function (xhr, status, error) {
                            swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                            stopLoadingOnButton(button, 'Atualizar despesa')
                        },
                        success: function (response) {
                            stopLoadingOnButton(button, 'Atualizar despesa')

                            if (response.ok) {
                                jQuery('#loadingSpinner').show()
                                jQuery('#editExpenseModal').modal('hide')

                                location.reload()
                            } else {
                                swalNotification('Cartão de crédito', response.message, 'error', 'Tá, entendi')
                            }
                        }
                    })
                } else {
                    let textMessage

                    if (response.invoice) {
                        textMessage = 'Atenção! TODAS as parcelas desta despesa serão removidas da fatura do cartão.'
                    } else {
                        textMessage = 'Atenção! TODAS as parcelas desta despesa serão adicionadas na fatura do cartão.'
                    }

                    Swal.fire({
                        title: 'Despesa com parcelamento, continuar?',
                        text: textMessage,
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sim, pode continuar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            jQuery.ajax({
                                url: 'expenses/update',
                                type: 'post',
                                dataType: 'json',
                                timeout: 20000,
                                data: {
                                    description_expense_edit: description,
                                    budgeted_amount_expense_edit: budgetedAmount,
                                    realized_amount_expense_edit: realizedAmount,
                                    credit_card_edit: creditCard,
                                    period_expense_edit: period,
                                    id_expense: idExpense,
                                    category_active: categoryActive,
                                    category_expense_edit: categoryExpense,
                                    payment_method_change: 'S' // Payment method was changed
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                },
                                beforeSend: function () {
                                    showLoadingOnButton(button, 'Carregando...')
                                },
                                error: function (xhr, status, error) {
                                    swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                                    stopLoadingOnButton(button, 'Atualizar despesa')
                                },
                                success: function (response) {
                                    stopLoadingOnButton(button, 'Atualizar despesa')

                                    if (response.ok) {
                                        jQuery('#loadingSpinner').show()
                                        jQuery('#editExpenseModal').modal('hide')
                                        location.reload()
                                    } else {
                                        swalNotification('Cartão de crédito', response.message, 'error', 'Tá, entendi')
                                        return false
                                    }
                                }
                            })
                        }
                    })
                }
            }
        })
    })

    jQuery(document).on('click', '.edit-recipe', function (e) {
        e.preventDefault()

        let id = $(this).attr('id')

        jQuery.ajax({
            url: `recipes/${id}`,
            type: 'get',
            dataType: 'json',
            timeout: 20000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                jQuery('#modalHeaderEditRecipe').hide()
                jQuery('#modalFooterEditRecipe').hide()
                jQuery('#form-edit-recipe').hide()
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
            },
            success: function (response) {
                jQuery('#modalHeaderEditRecipe').show()
                jQuery('#modalFooterEditRecipe').show()
                jQuery('#form-edit-recipe').show()
                jQuery('#loadingSpinnerRecipe').hide()

                jQuery('input[name="description_edit"]').val(response.description)
                jQuery('input[name="id_recipe"]').val(response.id)
                jQuery('input[name="category_active"]').val(response.category_active)

                Number(jQuery('select[name="category_edit"]').val(response.category))
                if (response.category_active != 1) {
                    jQuery('select[name="category_edit"]')
                        .attr('disabled', true)
                        .addClass('border-red')
                    jQuery('.msg-category-cancelled').css('display', 'block')
                } else {
                    jQuery('select[name="category_edit"]')
                        .attr('disabled', false)
                        .removeClass('border-red')
                    jQuery('.msg-category-cancelled').css('display', 'none')
                }

                Number(jQuery('input[name="repeat_next_months"]').val(response.repeat_next_months))

                jQuery('input[name="repeat_next_months"]').attr('disabled', true)
                if (response.repeat_next_months == 1) {
                    jQuery('input[name="repeat_next_months"]').attr('checked', true)
                    jQuery('.label-checkbox-repeat').text('Esta receita é repetida nos meses seguintes')
                } else {
                    jQuery('input[name="repeat_next_months"]').attr('checked', false)
                    jQuery('.label-checkbox-repeat').text('Esta receita é exclusiva do mês atual')
                }

                jQuery('input[name="budgeted_amount_edit"]').val(response.budgeted_amount)
            }
        })
    })

    jQuery(document).on('click', '.edit-category', function (e) {
        e.preventDefault()

        let id = $(this).attr('id')

        jQuery.ajax({
            url: `categories/${id}`,
            type: 'get',
            dataType: 'json',
            timeout: 20000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
            },
            success: function (response) {
                jQuery('input[name="id_category"]').val(response.id)

                jQuery('input[name="description_category_edit"]').val(response.description)

                let colorDiv = jQuery('input[name="color"]').parents()[0]
                if (response.belongs_to == '1') {
                    jQuery('input[name="color"]').attr('disabled', true)
                    colorDiv.style.display = 'none'
                } else {
                    colorDiv.style.display = 'block'
                    jQuery('input[name="color"]').val(response.color)
                }

                Number(jQuery('select[name="belongs_to_edit"]').val(response.belongs_to))
            }
        })
    })

    jQuery(document).on('click', '.edit-card', function (e) {
        e.preventDefault()

        let id = $(this).attr('id')

        jQuery.ajax({
            url: `cards/${id}`,
            type: 'get',
            dataType: 'json',
            timeout: 20000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                jQuery('#modalHeaderEditCard').hide()
                jQuery('#modalFooterEditCard').hide()
                jQuery('#form-edit-card').hide()
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
            },
            success: function (response) {
                jQuery('#modalHeaderEditCard').show()
                jQuery('#modalFooterEditCard').show()
                jQuery('#form-edit-card').show()
                jQuery('#loadingSpinnerCard').hide()

                jQuery('input[name="id_card"]').val(response.id)

                jQuery('input[name="card_description_edit"]').val(response.description)
                jQuery('input[name="card_number_edit"]').val(response.number)
                jQuery('input[name="invoice_day"]').val(response.invoice_day)
                Number(jQuery('select[name="card_flag_edit"]').val(response.flag))

                if (response.no_number) {
                    jQuery('#msg-no-card-number').hide()
                } else {
                    jQuery('#msg-no-card-number').show()
                }
            }
        })
    })

    jQuery(document).on('click', '.cancel-installment', function (e) {
        e.preventDefault()

        let id = $(this).attr('id')

        jQuery.ajax({
            url: `expenses/${id}`,
            type: 'get',
            dataType: 'json',
            timeout: 20000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                jQuery('#loadingSpinnerInfo').show()
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
            },
            success: function (response) {
                jQuery('input[name="id_expense"]').val(response.id)
            }
        })
    })

    jQuery(document).on('click', '.cancel-installments', function (e) {
        e.preventDefault()

        let id = $(this).attr('id')

        jQuery.ajax({
            url: `expenses/${id}`,
            type: 'get',
            dataType: 'json',
            timeout: 20000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                jQuery('#loadingSpinnerInfo').show()
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
            },
            success: function (response) {
                jQuery('input[name="id_expense"]').val(response.id)
            }
        })
    })

    jQuery(document).on('click', '.info-expense', function (e) {
        e.preventDefault()

        let id = $(this).attr('id')

        jQuery.ajax({
            url: `expenses/${id}`,
            type: 'get',
            dataType: 'json',
            timeout: 20000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                jQuery('#loadingSpinnerInfo').show()
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
            },
            success: function (response) {
                console.log(response)
                jQuery('#loadingSpinnerInfo').hide()

                jQuery('input[name="description_expense_info"]').val(response.description)
                Number(jQuery('select[name="category_expense_info"]').val(response.category))
                Number(jQuery('select[name="period_expense_info"]').val(response.period))
                Number(jQuery('select[name="installments_expense_info"]').val(response.installment))

                jQuery('input[name="repeat_next_months"]').attr('disabled', true)
                if (response.repeat_next_months == 1) {
                    jQuery('input[name="repeat_next_months"]').attr('checked', true)
                    jQuery('.label-checkbox-repeat').text('Esta receita é repetida nos meses seguintes')
                } else {
                    jQuery('input[name="repeat_next_months"]').attr('checked', false)
                    jQuery('.label-checkbox-repeat').text('Esta receita é exclusiva do mês atual')
                }

                jQuery('input[name="budgeted_amount_expense_info"]').val(response.budgeted_amount)
                jQuery('input[name="realized_amount_expense_info"]').val(response.realized_amount)
                jQuery('textarea[name="reason_cancelled_info"]').html(response.reason_cancelled)
                jQuery('#total_installments').html(response.installments)
            }
        })
    })

    jQuery(document).on('click', '.edit-expense', function (e) {
        e.preventDefault()

        let id = $(this).attr('id')

        jQuery.ajax({
            url: `expenses/${id}`,
            type: 'get',
            dataType: 'json',
            timeout: 20000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                jQuery('#modalHeaderEditExpense').hide()
                jQuery('#modalFooterEditExpense').hide()
                jQuery('#form-edit-expense').hide()
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
            },
            success: function (response) {
                jQuery('#modalHeaderEditExpense').show()
                jQuery('#modalFooterEditExpense').show()
                jQuery('#loadingSpinner').hide()
                jQuery('#form-edit-expense').show()

                jQuery('input[name="id_expense"]').val(response.id)
                jQuery('input[name="category_active"]').val(response.category_active)
                jQuery('input[name="description_expense_edit"]').val(response.description)
                Number(jQuery('select[name="category_expense_edit"]').val(response.category))

                if ((Number(response.budgeted_amount) - Number(response.realized_amount)) === 0) {
                    jQuery('.message-done').show()
                    jQuery('.update-expense').hide()
                    jQuery('#msg-info-lancar-valor').hide()
                    jQuery('#label-realized-value').html('Total lançado <small>(em R$)</small>')
                    jQuery('input[name="description_expense_edit"]').attr('disabled', true)
                    jQuery('input[name="budgeted_amount_expense_edit"]').attr('disabled', true)
                    jQuery('input[name="realized_amount_expense_edit"]').attr('disabled', true)
                    jQuery('input[name="realized_amount_expense_edit"]').val(response.realized_amount)
                    jQuery('#was_with_credit_card_edit').parent().css('display', 'none')

                    setTimeout(function () {
                        jQuery('select[name="category_expense_edit"]').attr('disabled', true)
                    }, 100)
                } else {
                    jQuery('.message-done').hide()
                    jQuery('.update-expense').show()
                    jQuery('#msg-info-lancar-valor').show()
                    jQuery('#label-realized-value').show()
                    jQuery('#label-realized-value').html('Lançar valor <small>(em R$)</small>')

                    jQuery('input[name="description_expense_edit"]').attr('disabled', false)
                    jQuery('input[name="budgeted_amount_expense_edit"]').attr('disabled', false)
                    jQuery('input[name="realized_amount_expense_edit"]').attr('disabled', false)
                    jQuery('input[name="realized_amount_expense_edit"]').val('')
                    jQuery('#was_with_credit_card_edit').parent().css('display', 'block')

                    setTimeout(function () {
                        jQuery('select[name="category_expense_edit"]').attr('disabled', false)
                    }, 100)
                }

                if (response.category_active != 1) {
                    jQuery('select[name="category_expense_edit"]')
                        .attr('disabled', true)
                        .addClass('border-red')
                    jQuery('.msg-category-cancelled').css('display', 'block')
                } else {
                    jQuery('select[name="category_expense_edit"]')
                        .attr('disabled', false)
                        .removeClass('border-red')
                    jQuery('.msg-category-cancelled').css('display', 'none')
                }

                if (response.period == 0) {
                    jQuery('input[name="period_expense_edit"]').val(response.period)
                } else {
                    Number(jQuery('select[name="period_expense_edit"]').val(response.period))
                }

                Number(jQuery('select[name="installments_expense_edit"]').val(response.installment))
                jQuery('select[name="installments_expense_edit"]').attr('disabled', true)
                jQuery('select[name="installments_expense_edit"]').css('cursor', 'not-allowed')

                jQuery('input[name="budgeted_amount_expense_edit"]').val(response.budgeted_amount)
                jQuery('input[name="total_realized_amount"]').val(response.realized_amount)

                if (response.has_history >= 1) {
                    jQuery('.history-btn').css('display', 'block')
                }

                if (response.budgeted_amount == response.realized_amount) {
                    jQuery('input[name="expense_paid"]').parent().hide()
                } else {
                    jQuery('input[name="expense_paid"]').parent().show()
                }

                if (response.credit_card) {
                    jQuery('#was_with_credit_card_edit').prop('checked', true)
                    jQuery('#expense_card_selection_edit').css('display', 'block')
                    jQuery('select[name="credit_card_edit"]').attr('disabled', false)
                    jQuery('select[name="credit_card_edit"]').val(response.credit_card)
                } else {
                    jQuery('#was_with_credit_card_edit').prop('checked', false)
                    jQuery('#expense_card_selection_edit').css('display', 'none')
                    jQuery('select[name="credit_card_edit"]').attr('disabled', true)
                    jQuery('select[name="credit_card_edit"]').val(0)
                }
            }
        })
    })

    jQuery(document).on('click', '.save-reason-cancellation', function (e) {
        e.preventDefault()

        let reasonCancelled = jQuery('textarea[name="reason_cancelled"]').val()
        let button = jQuery(this)

        if (!reasonCancelled) {
            swalNotification('Presta atenção aí', 'É necessário informar o motivo do cancelamento.', 'error', 'Tá, entendi')
            return false
        }

        jQuery.ajax({
            url: 'expenses/cancel/one',
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: jQuery('#form-expense-cancellation').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                jQuery('#loadingSpinnerInfo').show()
                showLoadingOnButton(button, 'Carregando...')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                stopLoadingOnButton(button, 'Cancelar parcela')
            },
            success: function (response) {
                stopLoadingOnButton(button, 'Cancelar parcela')

                if (response.ok) {
                    jQuery('input[name="id_expense"]').val('')

                    formExpenseCancellation.reset()
                    jQuery('#oneReasonCancellation').modal('hide')
                    location.reload()
                }
            }
        })
    })

    jQuery(document).on('click', '.save-reason-cancellation-all', function (e) {
        e.preventDefault()

        let reasonCancelled = jQuery('textarea[name="reason_cancelled_all"]').val()
        let button = jQuery(this)

        if (!reasonCancelled) {
            swalNotification('Presta atenção aí', 'É necessário informar o motivo do cancelamento.', 'error', 'Tá, entendi')
            return false
        }

        jQuery.ajax({
            url: 'expenses/cancel/all',
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: jQuery('#form-expense-cancellation-all').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                jQuery('#loadingSpinnerInfo').show()
                showLoadingOnButton(button, 'Carregando...')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                stopLoadingOnButton(button, 'Cancelar parcelas')
            },
            success: function (response) {
                stopLoadingOnButton(button, 'Cancelar parcelas')

                if (response.ok) {
                    formExpenseCancellation.reset()
                    jQuery('input[name="id_expense"]').val('')
                    jQuery('#allReasonCancellation').modal('hide')
                    location.reload()
                } else {
                    swalNotification('Houve um erro', response.message, 'error', 'Tá, entendi')
                }
            }
        })
    })

    jQuery(document).on('click', '#save-my-data', function (e) {
        e.preventDefault()

        let name = jQuery('input[name="name"]').val()
        let email = jQuery('input[name="email"]').val()
        let password = jQuery('input[name="password"]').val()
        let passwordConfirm = jQuery('input[name="password_confirmation"]').val()

        let button = jQuery(this)

        if (!name || !email) {
            swalNotification('Presta atenção aí', 'Os dados obrigatórios devem estar preenchidos.', 'error', 'Tá, entendi')
            return false
        }

        if (password != passwordConfirm) {
            swalNotification('Alteração de senha', 'Por favor, verifique se as senhas foram informadas corretamente.', 'error', 'Tá, entendi')
            return false
        }

        jQuery.ajax({
            url: 'config/store',
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: jQuery('#form-config-my-data').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                jQuery('#loadingSpinnerInfo').show()
                showLoadingOnButton(button, 'Carregando...')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                stopLoadingOnButton(button, 'Atualizar configuração')
            },
            success: function (response) {
                stopLoadingOnButton(button, 'Atualizar configuração')

                if (response.ok) {
                    Swal.fire({
                        title: 'Configuração',
                        text: response.message,
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Continuar',
                        allowOutsideClick: false
                    }).then(result => {
                        if (result.isConfirmed) {
                            location.reload()
                        }
                    })
                }
            }
        })
    })

    jQuery(document).on('click', '.delete-recipe', function () {
        let id = jQuery(this).attr('id')

        Swal.fire({
            title: 'Deseja realmente excluir?',
            text: "Esta ação não poderá ser desfeita",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, pode excluir'
        }).then((result) => {
            if (result.isConfirmed) {
                jQuery.ajax({
                    url: `recipes/destroy/${id}`,
                    type: 'get',
                    dataType: 'json',
                    timeout: 20000,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    error: function (xhr, status, error) {
                        swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                    },
                    success: function (response) {
                        if (response.ok) {
                            location.reload()
                            swalNotification('Removido', 'Sua receita foi removida com sucesso.', 'success', 'Continuar')
                        }
                    }
                })
            }
        })
    })

    jQuery(document).on('click', '.pay-invoice', function () {
        let invoiceId = jQuery(this).attr('data-js-invoice-id')
        let cardName = jQuery(this).attr('data-js-card-name')

        Swal.fire({
            title: 'Pagamento da fatura',
            text: `TODAS as despesas cadastradas no cartão de crédito ${cardName} serão pagas, deseja prosseguir?`,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, pode pagar'
        }).then((result) => {
            if (result.isConfirmed) {
                jQuery.ajax({
                    url: 'invoices/pay',
                    type: 'post',
                    dataType: 'json',
                    data: { invoiceId },
                    timeout: 20000,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    error: function (xhr, status, error) {
                        swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                    },
                    success: function (response) {
                        if (response.ok) {
                            location.reload()
                            swalNotification('Fatura paga', response.message, 'success', 'Continuar')
                        }
                    }
                })
            }
        })
    })

    jQuery(document).on('click', '.delete-category', function () {
        let id = jQuery(this).attr('id')

        Swal.fire({
            title: 'Deseja realmente excluir?',
            text: "Esta ação não poderá ser desfeita",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, pode excluir'
        }).then((result) => {
            if (result.isConfirmed) {
                jQuery.ajax({
                    url: `categories/destroy/${id}`,
                    type: 'get',
                    dataType: 'json',
                    timeout: 20000,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    error: function (xhr, status, error) {
                        swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                    },
                    success: function (response) {
                        if (response.ok) {
                            location.reload()
                            swalNotification('Removido', 'Categoria foi removida com sucesso', 'success', 'Continuar')
                        }
                    }
                })
            }
        })
    })

    jQuery(document).on('click', '.delete-card', function () {
        let id = jQuery(this).attr('id')

        Swal.fire({
            title: 'Deseja realmente excluir?',
            text: "Esta ação não poderá ser desfeita",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, pode excluir'
        }).then((result) => {
            if (result.isConfirmed) {
                jQuery.ajax({
                    url: `cards/destroy/${id}`,
                    type: 'get',
                    dataType: 'json',
                    timeout: 20000,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    error: function (xhr, status, error) {
                        swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                    },
                    success: function (response) {
                        if (response.ok) {
                            location.reload()
                            swalNotification('Removido', 'Cartão foi removido com sucesso', 'success', 'Continuar')
                        }
                    }
                })
            }
        })
    })

    jQuery(document).on('click', '.revert-installment', function () {
        let id = jQuery(this).attr('id')

        Swal.fire({
            title: 'Deseja realmente reverter este cancelamento?',
            text: "Esta ação irá reverter o cancelamento desta despesa/parcela",
            icon: 'info',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, pode reverter'
        }).then((result) => {
            if (result.isConfirmed) {
                jQuery.ajax({
                    url: `expenses/revert/${id}`,
                    type: 'post',
                    dataType: 'json',
                    timeout: 20000,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    error: function (xhr, status, error) {
                        swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                    },
                    success: function (response) {
                        if (response.ok) {
                            location.reload()
                            swalNotification('Revertido', 'Parcela foi revertida com sucesso', 'success', 'Continuar')
                        }
                    }
                })
            }
        })
    })

    jQuery(document).on('click', '.delete-expense', function () {
        let id = jQuery(this).attr('id')

        Swal.fire({
            title: 'Deseja realmente excluir?',
            text: "Esta ação não poderá ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, pode excluir'
        }).then((result) => {
            if (result.isConfirmed) {
                jQuery.ajax({
                    url: `expenses/destroy/${id}`,
                    type: 'get',
                    dataType: 'json',
                    timeout: 20000,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    error: function (xhr, status, error) {
                        swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                    },
                    success: function (response) {
                        if (response.ok) {
                            swalNotification('Removido', 'Despesa foi removida com sucesso', 'success', 'Continuar')
                            location.reload()
                        }
                    }
                })
            }
        })
    })

    jQuery(document).on('click', '.delete-total-expense', function () {
        let id = jQuery(this).attr('id')

        Swal.fire({
            title: 'Excluir todo o parcelamento?',
            text: 'Esta ação irá excluir TODAS as parcelas vinculadas à despesa selecionada.',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir parcelamento'
        }).then((result) => {
            if (result.isConfirmed) {
                jQuery.ajax({
                    url: `expenses/destroy/all/${id}`,
                    type: 'get',
                    dataType: 'json',
                    timeout: 20000,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    error: function (xhr, status, error) {
                        swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                    },
                    success: function (response) {
                        if (response.ok) {
                            swalNotification('Removidos', response.message, 'success', 'Continuar')

                            location.reload()
                        }
                    }
                })
            }
        })
    })

    jQuery(document).on('click', '.history-btn', function (e) {
        e.preventDefault()

        let idExpense = jQuery('input[name="id_expense"]').val()
        let button = jQuery(this)

        jQuery.ajax({
            url: `expenses/history/${idExpense}`,
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: jQuery('#form-config-my-data').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                jQuery('#loadingSpinnerHistory').show()
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                jQuery('#loadingSpinnerHistory').hide()
            },
            success: function (response) {
                if (response.ok) {
                    jQuery('#tbody-table-history').html('')
                    jQuery('#loadingSpinnerHistory').hide()

                    var rows = ''

                    jQuery(response.data).each(function (i, item) {
                        rows += `
                            <tr>
                            <td>R$ ${item.value}</td>
                                <td>${item.date}</td>
                            </tr>
                        `
                    })

                    jQuery('.table-history').show()
                    jQuery('#tbody-table-history').append(rows)
                    jQuery('#total-history').text(
                        `R$ ${response.totalHistory.toLocaleString('pt-br', { minimumFractionDigits: 2 })}`
                    )
                } else {
                    jQuery('#table-history').hide()
                }
            }
        })
    })

    jQuery(document).on('change', 'input[name="month"], input[name="year"]', function (e) {
        let month = jQuery('input[name="month"]:checked').val()
        let year = jQuery('input[name="year"]:checked').val()
        let appUrl = getAppUrl();

        window.location.href = `${appUrl}/dashboard?month=${month}&year=${year}`
    })

    jQuery(document).on('click', '.cancel-recipe', function (e) {
        e.preventDefault()

        formNewRecipe.reset()
        formEditRecipe.reset()

        jQuery('#loadingSpinner').show()
        jQuery('#loadingSpinnerRecipe').show()

        jQuery('input[name="repeat_next_months"]').attr('checked', false)
        jQuery('input[name="repeat_next_months"]').attr('disabled', false)

        sessionStorage.removeItem('reloadingRecipe')
    })

    jQuery(document).on('click', '.cancel-card', function (e) {
        jQuery('#loadingSpinnerCard').show()
    })

    jQuery(document).on('click', '.cancel-user', function (e) {
        jQuery('#loadingSpinnerUser').show()

        formNewUser.reset()
        formEditUser.reset()
    })

    jQuery(document).on('click', '.cancel-expense', function (e) {
        e.preventDefault()

        formNewExpense.reset()
        formNewRecipe.reset()

        jQuery('#loadingSpinner').show()
        jQuery('#loadingSpinnerRecipe').show()

        jQuery('input[name="budgeted_amount_expense"]').attr('placeholder', '*Valor orçado (R$)')
        jQuery('input[name="expense_paid"]').prop('checked', false)
        jQuery('input[name="repeat_next_months_expense"]').attr('checked', false)
        jQuery('input[name="repeat_next_months_expense"]').attr('disabled', false)

        jQuery('#expense_card_selection').hide()
        jQuery('.history-btn').css('display', 'none')

        jQuery('.message-done').hide()
        jQuery('.update-expense').show()
        jQuery('input[name="description_expense_edit"]').attr('disabled', false)

        setTimeout(function () {
            jQuery('select[name="category_expense_edit"]').attr('disabled', false)
        }, 100)

        sessionStorage.removeItem('realizedAmount')
        sessionStorage.removeItem('reloadingExpense')
    })

    jQuery(document).on('change', '#expense_paid', function (e) {
        let budgetedAmount = jQuery('input[name="budgeted_amount_expense_edit"]').val()
        let realizedAmount = jQuery('input[name="realized_amount_expense_edit"]').val()

        if (!sessionStorage.getItem('realizedAmount')) {
            sessionStorage.setItem('realizedAmount', realizedAmount)
        }

        if (jQuery(this).is(':checked')) {
            jQuery('input[name="realized_amount_expense_edit"]').val(budgetedAmount)
        } else {
            jQuery('input[name="realized_amount_expense_edit"]').val(sessionStorage.getItem('realizedAmount'))
        }
    })

    jQuery(document).on('change', '.was_with_credit_card', function (e) {
        if (jQuery(this).is(':checked')) {
            jQuery('#expense_card_selection').show()
            jQuery('#expense_card_selection').children().eq(1).attr('disabled', false)
        } else {
            jQuery('#expense_card_selection').hide()
            jQuery('#expense_card_selection').children().eq(1).attr('disabled', true)
            jQuery('select[name="credit_card"]').val(0)
        }
    })

    jQuery(document).on('change', '.was_with_credit_card_edit', function (e) {
        if (jQuery(this).is(':checked')) {
            jQuery('#expense_card_selection_edit').show()
            jQuery('#expense_card_selection_edit').children().eq(1).attr('disabled', false)
        } else {
            jQuery('#expense_card_selection_edit').hide()
            jQuery('#expense_card_selection_edit').children().eq(1).attr('disabled', true)
            jQuery('select[name="credit_card_edit"]').val(0)
        }
    })

    jQuery(document).on('click', '.eye-icon-password', function (e) {
        let type = jQuery(this).siblings()[0].type

        if (type == 'password') {
            jQuery(this).siblings()[0].type = 'text'
            jQuery(this).html('<i class="fas fa-eye"></i>')
        } else {
            jQuery(this).siblings()[0].type = 'password'
            jQuery(this).html('<i class="fas fa-eye-slash"></i>')
        }
    })

    jQuery(document).on('click', '.copy-to-clipboard', function (e) {
        let copyButtonTooltip = jQuery(this).parent()[0]
        let inputPassword = jQuery('input[name="password_user"]')[0]
        navigator.clipboard.writeText(inputPassword.value)

        let tooltip = new bootstrap.Tooltip(copyButtonTooltip)

        tooltip.show()

        setTimeout(function (e) {
            tooltip.hide()
        }, 1000)
    })

    jQuery(document).on('click', '.generate-new-password', function (e) {
        jQuery.ajax({
            url: 'users/password/generate',
            type: 'get',
            dataType: 'json',
            timeout: 20000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
            },
            success: function (response) {
                if (response.ok) {
                    jQuery('input[name="password_user"]').val(response.password)
                    jQuery('input[name="password"]').val(response.password)
                }
            }
        })
    })

    jQuery(document).on('click', '.save-user', function (e) {
        let name = jQuery('input[name="name_user"]').val()
        let email = jQuery('input[name="email_user"]').val()
        let password = jQuery('input[name="password_user"]').val()

        let button = jQuery(this)

        if (!name || !email || !password) {
            swalNotification('Presta atenção aí', 'Os dados obrigatórios devem estar preenchidos.', 'error', 'Tá, entendi')
            return false
        }

        jQuery.ajax({
            url: 'users/store',
            type: 'post',
            dataType: 'json',
            data: jQuery('#form-new-user').serialize(),
            timeout: 20000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                showLoadingOnButton(button, 'Carregando...')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                stopLoadingOnButton(button, 'Salvar usuário')
            },
            success: function (response) {
                stopLoadingOnButton(button, 'Salvar usuário')

                swalNotification('Usuário criado', response.message, 'success', 'Fechar')
                location.reload()
            }
        })
    })

    jQuery(document).on('click', '.edit-user', function (e) {
        let id = jQuery(this).attr('id')

        jQuery.ajax({
            url: `users/${id}`,
            type: 'get',
            dataType: 'json',
            timeout: 20000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                jQuery('#modalHeaderEditUser').hide()
                jQuery('#modalFooterEditUser').hide()
                jQuery('#form-edit-user').hide()
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
            },
            success: function (response) {
                jQuery('#modalHeaderEditUser').show()
                jQuery('#modalFooterEditUser').show()
                jQuery('#form-edit-user').show()
                jQuery('#loadingSpinnerUser').hide()

                if (response.ok) {
                    jQuery('input[name="id_user"]').val(response.user.id)
                    jQuery('input[name="name"]').val(response.user.name)
                    jQuery('input[name="email"]').val(response.user.email)
                    jQuery('input[name="email"]').val(response.user.email)
                    jQuery('select[name="role"]').val(response.user.role)
                    jQuery('select[name="first_access"]').val(response.user.first_access)
                    jQuery('select[name="active"]').val(response.user.active)
                } else {
                    location.reload()
                }
            }
        })
    })

    jQuery(document).on('click', '.update-user', function (e) {
        let name = jQuery('input[name="name"]').val()
        let email = jQuery('input[name="email"]').val()

        let button = jQuery(this)

        if (!name || !email) {
            swalNotification('Presta atenção aí', 'Os dados obrigatórios devem estar preenchidos.', 'error', 'Tá, entendi')
            return false
        }

        jQuery.ajax({
            url: 'users/update',
            type: 'post',
            dataType: 'json',
            data: jQuery('#form-edit-user').serialize(),
            timeout: 20000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                showLoadingOnButton(button, 'Carregando...')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                stopLoadingOnButton(button, 'Atualizar usuário')
            },
            success: function (response) {
                if (response.ok) {
                    swalNotification('Atualizado', response.message, 'success', 'Fechar')
                    stopLoadingOnButton(button, 'Atualizar usuário')
                    location.reload()
                } else {
                    stopLoadingOnButton(button, 'Atualizar usuário')
                    swalNotification('Houve um erro', 'Erro interno ao atualizar usuário.', 'error', 'Tentar novamente')
                }
            }
        })
    })

    jQuery(document).on('click', '.extend-installment', function (e) {
        let id = jQuery(this).attr('id')

        Swal.fire({
            title: 'Deseja realmente prorrogar?',
            text: 'Esta ação irá adiar TODAS as parcelas não pagas desta despesa em 1 (um) mês.',
            icon: 'info',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, prorrogar'
        }).then((result) => {
            if (result.isConfirmed) {
                jQuery.ajax({
                    url: `expenses/extend/${id}`,
                    type: 'post',
                    dataType: 'json',
                    timeout: 20000,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    error: function (xhr, status, error) {
                        swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                    },
                    success: function (response) {
                        if (response.ok) {
                            swalNotification('Prorrogado', 'As parcelas da despesa foram prorrogadas com sucesso.', 'success', 'Continuar')

                            location.reload()
                        }
                    }
                })
            }
        })
    })

    jQuery(document).on('click', '#search-expenses-by-categories', function (e) {
        let button = jQuery(this)

        jQuery.ajax({
            url: 'search',
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: jQuery('#form-search-expenses-by-categories').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function () {
                showLoadingOnButton(button, 'Carregando...')
            },
            error: function (xhr, status, error) {
                swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                stopLoadingOnButton(button, '<i class="fas fa-search"></i> Pesquisar')
            },
            success: function (response) {
                stopLoadingOnButton(button, '<i class="fas fa-search"></i> Pesquisar')

                if (response.ok) {
                    jQuery('#btn-accordion-search').click()

                    let table = `
                    <div class="accordion" id="accordionResults">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingResults">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseResults" aria-expanded="true" aria-controls="collapseResults">
                                <strong>Resultados da pesquisa</strong>
                            </button>
                            </h2>
                            <div id="collapseResults" class="accordion-collapse collapse show" aria-labelledby="headingResults" data-bs-parent="#accordionResults">
                                <div class="accordion-body">`

                    jQuery(response.data).each((index, item) => {
                        let hr = '<hr />'

                        if (index == 0) {
                            hr = ''
                        }

                        if (item.expenses.length == 0) {
                            table += `
                                ${hr}
                                <div class="">
                                    <h5>${item.category}</h5>
                                </div>
                                <span class="text gray-text-color">Não foram encontrados registros.</span>
                            `
                        } else {
                            table += `
                                <div class="">
                                    <h5>${item.category}</h5>
                                </div>
                                <table id="table-reports" class="table bg-white table-hover" style="vertical-align: middle">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>Descrição da despesa</th>
                                            <th>Parcela(s)</th>
                                            <th>Mês/Ano</th>
                                            <th>Vlr. Orçado (R$)</th>
                                            <th>Vlr. Realizado (R$)</th>
                                            <th>Vlr. Pendente (R$)</th>
                                            <th>Lançado por</th>
                                        </tr>
                                    </thead>
                                    <tbody class="gray-text-color">`

                            let budgetedSum = 0
                            let realizedSum = 0
                            let pendingSum = 0
                            jQuery(item.expenses).each((i, expense) => {
                                budgetedSum += Number(expense.budgeted_amount)
                                realizedSum += Number(expense.realized_amount)
                                pendingSum += Number(expense.pending_amount)

                                table += `
                                    <tr>
                                        <td>${expense.expense_name}</td>
                                        <td>${expense.installment}</td>
                                        <td>${expense.month_name}/${expense.year}</td>
                                        <td>${amountParseFloat(expense.budgeted_amount)}</td>
                                        <td>${amountParseFloat(expense.realized_amount)}</td>
                                        <td>${amountParseFloat(expense.pending_amount)}</td>
                                        <td>${expense.user_name}</td>
                                    </tr>

                                `
                            })
                            table += `
                                <tr style="border: 1px solid #fff;">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><strong>${amountParseFloat(budgetedSum)}</strong></td>
                                    <td><strong>${amountParseFloat(realizedSum)}</strong></td>
                                    <td><strong>${amountParseFloat(pendingSum)}</strong></td>
                                    <td></td>
                                </tr>
                            `

                            table += `</tbody>
                                </table>
                            `
                        }
                    })

                    table += `</div>
                                </div>
                            </div>
                        </div>
                    `

                    jQuery('#table-reports').html(table)
                } else {
                    swalNotification('Presta atenção aí', response.message, 'error', 'Tá, entendi')
                }
            }
        })
    })
})
