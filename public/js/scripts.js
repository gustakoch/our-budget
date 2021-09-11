document.addEventListener('DOMContentLoaded', function() {
    const formNewRecipe = document.querySelector('#form-new-recipe')
    const formEditRecipe = document.querySelector('#form-edit-recipe')
    const formNewExpense = document.querySelector('#form-new-expense')
    const formEditExpense = document.querySelector('#form-edit-expense')
    const formExpenseCancellation = document.querySelector('#form-expense-cancellation')
    const formExpenseCancellations = document.querySelector('#form-expense-cancellation-all')

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
            success: function(response) {
                appUrl = response.appUrl
            }
        })

        return appUrl
    }

    jQuery(document).on('click', '.save-recipe', function(e) {
        e.preventDefault()

        let description = jQuery('input[name="description"]').val()
        let category = Number(jQuery('select[name="category"]').val())
        let amount = jQuery('input[name="budgeted_amount"]').val()

        if (!description || !category || !amount) {
            Swal.fire({
                title: 'Presta atenção aí',
                text: "Os dados obrigatórios devem ser preenchidos!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tá, entendi'
            })
            return false
        }

        if (amount == '0') {
            Swal.fire({
                title: 'Oops',
                text: "O valor informado deve ser maior do que zero!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tentar novamente'
            })
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
            error: function(xhr, status, error) {
                console.log(xhr.reponseText)
            },
            success: function(response) {
                if (response.ok) {
                    jQuery('#addRecipeModal').modal('hide')
                    location.reload()
                }
            }
        })
    })

    jQuery(document).on('click', '.save-recipe-continue', function(e) {
        e.preventDefault()

        let description = jQuery('input[name="description"]').val()
        let category = Number(jQuery('select[name="category"]').val())
        let amount = jQuery('input[name="budgeted_amount"]').val()

        if (!description || !category || !amount) {
            Swal.fire({
                title: 'Presta atenção aí',
                text: "Os dados obrigatórios devem ser preenchidos!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tá, entendi'
            })
            return false
        }

        if (amount == '0') {
            Swal.fire({
                title: 'Oops',
                text: "O valor informado deve ser maior do que zero!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tentar novamente'
            })
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
            error: function(xhr, status, error) {
                console.log(xhr.reponseText)
            },
            success: function(response) {
                if (response.ok) {
                    jQuery('#addRecipeModal').modal('hide')

                    sessionStorage.setItem('reloadingRecipe', 'true');
                    document.location.reload();
                }
            }
        })
    })

    jQuery(document).on('click', '.save-expense-continue', function(e) {
        e.preventDefault()

        let description = jQuery('input[name="description_expense"]').val()
        let category = Number(jQuery('select[name="category_expense"]').val())
        let amount = jQuery('input[name="budgeted_amount_expense"]').val()

        if (!description || !category || !amount) {
            Swal.fire({
                title: 'Presta atenção aí',
                text: "Os dados obrigatórios devem ser preenchidos!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tá, entendi'
            })
            return false
        }

        if (amount == '0') {
            Swal.fire({
                title: 'Oops',
                text: "O valor informado deve ser maior do que zero!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tentar novamente'
            })
            return false
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
            error: function(xhr, status, error) {
                console.log(xhr.reponseText)
            },
            success: function(response) {
                if (response.ok) {
                    jQuery('#addExpenseModal').modal('hide')

                    sessionStorage.setItem('reloadingExpense', 'true');
                    document.location.reload();
                }
            }
        })
    })

    window.onload = function() {
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

    jQuery(document).on('click', '.save-category', function(e) {
        e.preventDefault()

        let description = jQuery('input[name="description_category"]').val()
        let belongsTo = Number(jQuery('select[name="belongs_to"]').val())

        if (!description || !belongsTo) {
            Swal.fire({
                title: 'Presta atenção aí',
                text: "Os dados obrigatórios devem ser preenchidos!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tá, entendi'
            })
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
            error: function(xhr, status, error) {
                console.log(xhr.reponseText)
            },
            success: function(response) {
                if (response.ok) {
                    jQuery('#addCategoryModal').modal('hide')
                    location.reload()
                }
            }
        })
    })

    jQuery(document).on('click', '.save-card', function(e) {
        e.preventDefault()

        let description = jQuery('input[name="card_description"]').val()
        let invoiceDay = jQuery('input[name="invoice_day"]').val()

        if (!description || !invoiceDay) {
            Swal.fire({
                title: 'Presta atenção aí',
                text: "Por favor, informe a descrição e o dia de vencimento da fatura!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tá, entendi'
            })
            return false
        }

        if (invoiceDay <= 0 || invoiceDay > 31) {
            Swal.fire({
                title: 'Olha só...',
                text: "Você precisa informar algum valor entre 1 e 31, blza?",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tá, entendi'
            })
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
            error: function(xhr, status, error) {
                console.log(xhr.reponseText)
            },
            success: function(response) {
                if (response.ok) {
                    jQuery('#addCardModal').modal('hide')
                    location.reload()
                } else {
                    Swal.fire({
                        title: 'Oops...',
                        text: response.message,
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Tá, entendi'
                    })
                    return false
                }
            }
        })
    })

    jQuery(document).on('click', '#first-access', function(e) {
        e.preventDefault()

        let name = jQuery('input[name="name"]').val()
        let email = jQuery('input[name="email"]').val()
        let password = jQuery('input[name="password"]').val()
        let password_confirmation = jQuery('input[name="password_confirmation"]').val()
        let gradle_format = jQuery('input[name="gradle_format"]').is(':checked')

        if (!name || !email || !password || !password_confirmation || !gradle_format) {
            Swal.fire({
                title: 'Presta atenção aí',
                text: "Os dados obrigatórios devem ser preenchidos!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tá, entendi'
            })
            return false
        }

        if (password != password_confirmation) {
            Swal.fire({
                title: 'Presta atenção aí',
                text: "As novas senhas não conferem! Por favor, verifique os dados e tente novamente",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tá, entendi'
            })
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
            error: function(xhr, status, error) {
                console.log(xhr.reponseText)
            },
            success: function(response) {
                let appUrl = getAppUrl()

                window.location.href = `${appUrl}/dashboard`
            }
        })
    })

    jQuery(document).on('change', 'select[name="installments_expense"]', function(e) {
        jQuery('input[name="repeat_next_months_expense"]').prop('checked', false)

        if (jQuery(this).val() == '1') {
            jQuery('input[name="budgeted_amount_expense"]').attr('placeholder', 'Informe o valor orçado (R$)')
            jQuery('input[name="repeat_next_months_expense"]').attr('disabled', false)

            return false
        }

        jQuery('input[name="budgeted_amount_expense"]').attr('placeholder', 'Informe o valor da parcela (R$)')
        jQuery('input[name="repeat_next_months_expense"]').attr('disabled', true)
    })

    jQuery(document).on('click', '.save-expense', function(e) {
        e.preventDefault()

        let description = jQuery('input[name="description_expense"]').val()
        let category = Number(jQuery('select[name="category_expense"]').val())
        let amount = jQuery('input[name="budgeted_amount_expense"]').val()

        if (!description || !category || !amount) {
            Swal.fire({
                title: 'Presta atenção aí',
                text: "Os dados obrigatórios devem ser preenchidos!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tá, entendi'
            })
            return false
        }

        if (amount == '0') {
            Swal.fire({
                title: 'Oops',
                text: "O valor informado deve ser maior do que zero!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tentar novamente'
            })
            return false
        }

        let isWithCreditCard = jQuery('#was_with_credit_card').is(':checked')

        if (isWithCreditCard) {
            let creditCard = Number(jQuery('select[name="credit_card"]').val())

            if (!creditCard) {
                Swal.fire({
                    title: 'Cartão de crédito',
                    text: "Por favor, selecione o cartão de crédito para vincular na despesa!",
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Tá, entendi'
                })
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
            error: function(xhr, status, error) {
                console.log(xhr.reponseText)
            },
            success: function(response) {
                if (response.ok) {
                    jQuery('#addExpenseModal').modal('hide')
                    location.reload()

                    sessionStorage.removeItem('reloadingExpense')
                }
            }
        })
    })

    jQuery(document).on('click', '.update-recipe', function(e) {
        e.preventDefault()

        let description = jQuery('input[name="description_edit"]').val()
        let amount = jQuery('input[name="budgeted_amount_edit"]').val()

        if (!description || !amount) {
            Swal.fire({
                title: 'Presta atenção aí',
                text: "Os dados obrigatórios devem estar preenchidos!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Continuar atualização'
            })
            return false
        }

        if (amount == '0' || amount == 0) {
            Swal.fire({
                title: 'Oops',
                text: "O valor informado deve ser maior do que zero!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tentar novamente'
            })
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
            error: function(xhr, status, error) {
                console.log(xhr.responseText)
            },
            success: function(response) {
                if (response.ok) {
                    jQuery('#editRecipeModal').modal('hide')
                    location.reload()
                }
            }
        })
    })

    jQuery(document).on('click', '.update-category', function(e) {
        e.preventDefault()

        let description = jQuery('input[name="description_category_edit"]').val()
        let belongsTo = Number(jQuery('select[name="belongs_to_edit"]').val())

        if (!description || !belongsTo) {
            Swal.fire({
                title: 'Presta atenção aí',
                text: "Os dados obrigatórios devem estar preenchidos!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Continuar atualização'
            })
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
            error: function(xhr, status, error) {
                console.log(xhr.responseText)
            },
            success: function(response) {
                jQuery('#loadingSpinner').show()

                if (response.ok) {
                    jQuery('#editCategoryModal').modal('hide')
                    location.reload()
                }
            }
        })
    })

    jQuery(document).on('click', '.update-card', function(e) {
        e.preventDefault()

        let description = jQuery('input[name="card_description_edit"]').val()
        let invoiceDay = jQuery('input[name="invoice_day"]').val()

        if (!description || !invoiceDay) {
            Swal.fire({
                title: 'Presta atenção aí',
                text: "Por favor, informe a descrição e o dia de vencimento da fatura!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Continuar atualização'
            })
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
            error: function(xhr, status, error) {
                console.log(xhr.responseText)
            },
            success: function(response) {
                if (response.ok) {
                    jQuery('#editCardModal').modal('hide')
                    location.reload()
                }
            }
        })
    })

    jQuery(document).on('click', '.update-expense', function(e) {
        e.preventDefault()

        let description = jQuery('input[name="description_expense_edit"]').val()
        let period = Number(jQuery('select[name="period_expense_edit"]').val())
        let budgetedAmount = Number(jQuery('input[name="budgeted_amount_expense_edit"]').val())
        let realizedAmount = Number(jQuery('input[name="realized_amount_expense_edit"]').val())
        let creditCard = Number(jQuery('select[name="credit_card_edit"]').val())
        let isWithCreditCard = jQuery('#was_with_credit_card_edit').is(':checked')

        if (!description || !budgetedAmount) {
            Swal.fire({
                title: 'Presta atenção aí',
                text: "Os dados obrigatórios devem estar preenchidos!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Continuar atualização'
            })
            return false
        }

        if (budgetedAmount == '0' || budgetedAmount == 0) {
            Swal.fire({
                title: 'Oops...',
                text: "Por favor, informe um valor maior do que zero!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tentar novamente'
            })
            return false
        }

        if (realizedAmount > budgetedAmount) {
            Swal.fire({
                title: 'Oops...',
                text: "Operação não permitida! O valor realizado não deve ser maior do que o orçado.",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tentar novamente'
            })
            return false
        }

        if (isWithCreditCard) {
            if (!creditCard) {
                Swal.fire({
                    title: 'Cartão de crédito',
                    text: "Por favor, selecione o cartão de crédito para vincular na despesa",
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Tentar novamente'
                })
                return false
            }
        }

        jQuery.ajax({
            url: 'expenses/update',
            type: 'post',
            dataType: 'json',
            timeout: 20000,
            data: jQuery('#form-edit-expense').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText)
            },
            success: function(response) {
                jQuery('#loadingSpinner').show()

                if (response.ok) {
                    jQuery('#editExpenseModal').modal('hide')
                    location.reload()
                }
            }
        })
    })

    jQuery(document).on('click', '.edit-recipe', function(e) {
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
            beforeSend: function() {
                jQuery('#modalHeaderEditRecipe').hide()
                jQuery('#modalFooterEditRecipe').hide()
                jQuery('#form-edit-recipe').hide()
            },
            error: function(xhr, status, error) {
                console.log(xhr.reponseText)
            },
            success: function(response) {
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

    jQuery(document).on('click', '.edit-category', function(e) {
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
            error: function(xhr, status, error) {
                console.log(xhr.reponseText)
            },
            success: function(response) {
                jQuery('input[name="id_category"]').val(response.id)

                jQuery('input[name="description_category_edit"]').val(response.description)
                Number(jQuery('select[name="belongs_to_edit"]').val(response.belongs_to))
            }
        })
    })

    jQuery(document).on('click', '.edit-card', function(e) {
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
            beforeSend: function() {
                jQuery('#modalHeaderEditCard').hide()
                jQuery('#modalFooterEditCard').hide()
                jQuery('#form-edit-card').hide()
            },
            error: function(xhr, status, error) {
                console.log(xhr.reponseText)
            },
            success: function(response) {
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

    jQuery(document).on('click', '.cancel-installment', function(e) {
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
            beforeSend: function() {
                jQuery('#loadingSpinnerInfo').show()
            },
            error: function(xhr, status, error) {
                console.log(xhr.reponseText)
            },
            success: function(response) {
                jQuery('input[name="id_expense"]').val(response.id)
            }
        })
    })

    jQuery(document).on('click', '.cancel-installments', function(e) {
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
            beforeSend: function() {
                jQuery('#loadingSpinnerInfo').show()
            },
            error: function(xhr, status, error) {
                console.log(xhr.reponseText)
            },
            success: function(response) {
                jQuery('input[name="id_expense"]').val(response.id)
            }
        })
    })

    jQuery(document).on('click', '.info-expense', function(e) {
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
            beforeSend: function() {
                jQuery('#loadingSpinnerInfo').show()
            },
            error: function(xhr, status, error) {
                console.log(xhr.reponseText)
            },
            success: function(response) {
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

    jQuery(document).on('click', '.edit-expense', function(e) {
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
            beforeSend: function() {
                jQuery('#modalHeaderEditExpense').hide()
                jQuery('#modalFooterEditExpense').hide()
                jQuery('#form-edit-expense').hide()
            },
            error: function(xhr, status, error) {
                console.log(xhr.reponseText)
            },
            success: function(response) {
                console.log(response)

                jQuery('#modalHeaderEditExpense').show()
                jQuery('#modalFooterEditExpense').show()
                jQuery('#form-edit-expense').show()
                jQuery('#loadingSpinner').hide()

                jQuery('input[name="id_expense"]').val(response.id)
                jQuery('input[name="category_active"]').val(response.category_active)
                jQuery('input[name="description_expense_edit"]').val(response.description)
                Number(jQuery('select[name="category_expense_edit"]').val(response.category))

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

                jQuery('input[name="budgeted_amount_expense_edit"]').val(response.budgeted_amount)
                jQuery('input[name="realized_amount_expense_edit"]').val(response.realized_amount)

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

    jQuery(document).on('click', '.save-reason-cancellation', function(e) {
        e.preventDefault()

        let reasonCancelled = jQuery('textarea[name="reason_cancelled"]').val()

        if (!reasonCancelled) {
            Swal.fire({
                title: 'Presta atenção aí',
                text: "É necessário informar o motivo do cancelamento!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tá, entendi'
            })
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
            error: function(xhr, status, error) {
                console.log(xhr.reponseText)
            },
            success: function(response) {
                if (response.ok) {
                    jQuery('input[name="id_expense"]').val('')

                    formExpenseCancellation.reset()
                    jQuery('#oneReasonCancellation').modal('hide')
                    location.reload()
                }
            }
        })
    })

    jQuery(document).on('click', '.save-reason-cancellation-all', function(e) {
        e.preventDefault()

        let reasonCancelled = jQuery('textarea[name="reason_cancelled_all"]').val()

        if (!reasonCancelled) {
            Swal.fire({
                title: 'Presta atenção aí',
                text: "É necessário informar o motivo do cancelamento!",
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tá, entendi'
            })
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
            error: function(xhr, status, error) {
                console.log(xhr.reponseText)
            },
            success: function(response) {
                if (response.ok) {
                    formExpenseCancellation.reset()
                    jQuery('input[name="id_expense"]').val('')
                    jQuery('#allReasonCancellation').modal('hide')
                    location.reload()
                } else {
                    Swal.fire({
                        title: 'Oops...',
                        text: response.message,
                        icon: 'warning',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Tá bom, fechar'
                    })
                }
            }
        })
    })

    jQuery(document).on('click', '.delete-recipe', function() {
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
                    url: `recipes/destroy/${id}`,
                    type: 'get',
                    dataType: 'json',
                    timeout: 20000,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    error: function(xhr, status, error) {
                        console.log('error', error)
                    },
                    success: function(response) {
                        if (response.ok) {
                            location.reload()

                            Swal.fire({
                                title: 'Removido',
                                text: "Sua receita foi removida com sucesso",
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Continuar'
                            })
                        }
                    }
                })
            }
        })
    })

    jQuery(document).on('click', '.delete-category', function() {
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
                    url: `categories/destroy/${id}`,
                    type: 'get',
                    dataType: 'json',
                    timeout: 20000,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    error: function(xhr, status, error) {
                        console.log('error', error)
                    },
                    success: function(response) {
                        if (response.ok) {
                            location.reload()

                            Swal.fire({
                                title: 'Removido',
                                text: "Sua categoria foi removida com sucesso",
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Continuar'
                            })
                        }
                    }
                })
            }
        })
    })

    jQuery(document).on('click', '.delete-card', function() {
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
                    url: `cards/destroy/${id}`,
                    type: 'get',
                    dataType: 'json',
                    timeout: 20000,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    error: function(xhr, status, error) {
                        console.log('error', error)
                    },
                    success: function(response) {
                        if (response.ok) {
                            location.reload()

                            Swal.fire({
                                title: 'Removido',
                                text: "Este cartão foi removido com sucesso",
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Continuar'
                            })
                        }
                    }
                })
            }
        })
    })

    jQuery(document).on('click', '.revert-installment', function() {
        let id = jQuery(this).attr('id')

        Swal.fire({
            title: 'Deseja realmente reverter este cancelamento?',
            text: "Esta ação irá reverter o cancelamento anteriormente realizado.",
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
                    error: function(xhr, status, error) {
                        console.log('error', error)
                    },
                    success: function(response) {
                        if (response.ok) {
                            location.reload()

                            Swal.fire({
                                title: 'Revertido',
                                text: "Sua parcela foi revertida com sucesso",
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Continuar'
                            })
                        }
                    }
                })
            }
        })
    })

    jQuery(document).on('click', '.delete-expense', function() {
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
                    error: function(xhr, status, error) {
                        console.log(status, error)
                    },
                    success: function(response) {
                        if (response.ok) {
                            Swal.fire({
                                title: 'Removido',
                                text: "Sua despesa foi removida com sucesso",
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Continuar'
                            })

                            location.reload()
                        }
                    }
                })
            }
        })
    })

    jQuery('#referenceMonth').change(function(e) {
        let month = $(this).val()
        let appUrl = getAppUrl();

        window.location.href = `${appUrl}/dashboard?month=${month}`
    })

    function amountParseFloat(amount) {
        return parseFloat(amount).toLocaleString('pt-br', {
            minimumFractionDigits: 2, maximumFractionDigits: 2
        })
    }

    jQuery(document).on('click', '.cancel-recipe', function(e) {
        e.preventDefault()

        formNewRecipe.reset()
        formEditRecipe.reset()

        jQuery('#loadingSpinner').show()
        jQuery('#loadingSpinnerRecipe').show()

        jQuery('input[name="repeat_next_months"]').attr('checked', false)
        jQuery('input[name="repeat_next_months"]').attr('disabled', false)

        sessionStorage.removeItem('reloadingRecipe')
    })

    jQuery(document).on('click', '.cancel-card', function(e) {
        jQuery('#loadingSpinnerCard').show()
    })

    jQuery(document).on('click', '.cancel-expense', function(e) {
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

        sessionStorage.removeItem('realizedAmount')
        sessionStorage.removeItem('reloadingExpense')
    })

    jQuery(document).on('change', '#expense_paid', function(e) {
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

    jQuery(document).on('change', '.was_with_credit_card', function(e) {
        if (jQuery(this).is(':checked')) {
            jQuery('#expense_card_selection').show()
            jQuery('#expense_card_selection').children().eq(1).attr('disabled', false)
        } else {
            jQuery('#expense_card_selection').hide()
            jQuery('#expense_card_selection').children().eq(1).attr('disabled', true)
            jQuery('select[name="credit_card"]').val(0)
        }
    })

    jQuery(document).on('change', '.was_with_credit_card_edit', function(e) {
        if (jQuery(this).is(':checked')) {
            jQuery('#expense_card_selection_edit').show()
            jQuery('#expense_card_selection_edit').children().eq(1).attr('disabled', false)
        } else {
            jQuery('#expense_card_selection_edit').hide()
            jQuery('#expense_card_selection_edit').children().eq(1).attr('disabled', true)
            jQuery('select[name="credit_card_edit"]').val(0)
        }
    })
})
