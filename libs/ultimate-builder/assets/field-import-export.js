(function ($) {

    var UFBuilderIO = window.UFBuilderIO = {};

    // Bilingual strings, resolved via BUILDER_GLOBALS.locale (same pattern as field-ultimate-builder.js)
    var I18N = {
        export_modal_title: { es: 'Exportar contenido del builder', en: 'Export builder content' },
        export_modal_description: { es: 'Exporta el contenido actual del builder como archivo JSON, o cópialo al portapapeles.', en: 'Export the current builder content as a JSON file, or copy it to the clipboard.' },
        download_button: { es: 'Descargar archivo', en: 'Download file' },
        copy_button: { es: 'Copiar al portapapeles', en: 'Copy to clipboard' },
        copied_feedback: { es: '¡Copiado al portapapeles!', en: 'Copied to clipboard!' },
        copy_fallback_feedback: { es: 'No se pudo copiar automáticamente, selecciona y copia el texto manualmente.', en: 'Could not copy automatically, please select and copy manually.' },

        import_modal_title: { es: 'Importar contenido del builder', en: 'Import builder content' },
        import_modal_description: { es: 'Pega el JSON exportado abajo, o sube un archivo .json.', en: 'Paste the exported JSON below, or upload a .json file.' },
        import_textarea_placeholder: { es: 'Pega aquí el JSON exportado', en: 'Paste exported JSON here' },
        import_submit: { es: 'Importar', en: 'Import' },
        import_submitting: { es: 'Importando...', en: 'Importing...' },
        import_cancel: { es: 'Cancelar', en: 'Cancel' },

        invalid_json: { es: 'JSON inválido. Revisa el contenido pegado.', en: 'Invalid JSON. Please check the pasted content.' },
        missing_fields: { es: 'El objeto pegado no tiene los campos requeridos (theme_version, post_type, page_content, page_content_datastore).', en: 'The pasted object is missing required fields (theme_version, post_type, page_content, page_content_datastore).' },
        version_mismatch_detail: { es: 'Este contenido fue exportado desde otra versión del tema (%imported%) y no se puede importar en la versión %current%.', en: 'This content was exported from a different theme version (%imported%) and cannot be imported into version %current%.' },
        post_type_mismatch_detail: { es: 'Este contenido fue exportado desde otro tipo de contenido (%imported%) y no se puede importar en "%current%".', en: 'This content was exported from a different post type (%imported%) and cannot be imported into "%current%".' },
        confirm_overwrite: { es: 'Esto sobrescribirá el contenido actual del builder para esta entrada. ¿Continuar?', en: 'This will overwrite the current builder content for this post. Continue?' },
        request_failed: { es: 'La solicitud de importación falló. Inténtalo de nuevo.', en: 'Import request failed. Please try again.' },

        error_nonce_invalid: { es: 'Falló la verificación de seguridad, recarga la página e inténtalo de nuevo.', en: 'Security check failed, please reload the page and try again.' },
        error_no_permissions: { es: 'No tienes permisos para editar esta entrada.', en: 'You do not have permission to edit this post.' },
        error_invalid_payload: { es: 'El objeto pegado no tiene los campos requeridos.', en: 'The pasted object is missing required fields.' },
        error_version_mismatch: { es: 'Este contenido fue exportado desde otra versión del tema.', en: 'This content was exported from a different theme version.' },
        error_post_type_mismatch: { es: 'Este contenido fue exportado desde otro tipo de contenido.', en: 'This content was exported from a different post type.' },
        error_field_not_found: { es: 'No se encontró el campo del builder para esta entrada.', en: 'Could not find the builder field for this post.' },
        error_generic: { es: 'La importación falló.', en: 'Import failed.' }
    };

    function t(key, replacements) {
        var locale = (BUILDER_GLOBALS.locale || 'en');
        var entry = I18N[key];
        var text = entry ? (entry[locale] || entry.en) : key;

        if (replacements) {
            $.each(replacements, function (placeholder, value) {
                text = text.replace('%' + placeholder + '%', value);
            });
        }

        return text;
    }

    /**
     * Builds a minimal overlay modal and appends it to the body.
     */
    function buildModal(title, bodyHtml) {
        var $overlay = $('<div class="uf-io-modal-overlay"></div>');
        var $modal = $(
            '<div class="uf-io-modal">' +
                '<div class="uf-io-modal-header"><h2></h2><button type="button" class="uf-io-modal-close">&times;</button></div>' +
                '<div class="uf-io-modal-body"></div>' +
            '</div>'
        );

        $modal.find('.uf-io-modal-header h2').text(title);
        $modal.find('.uf-io-modal-body').html(bodyHtml);
        $overlay.append($modal);
        $('body').append($overlay);

        $modal.find('.uf-io-modal-close').on('click', function () {
            $overlay.remove();
        });

        // Close when clicking outside of the modal box
        $overlay.on('click', function (e) {
            if (e.target === $overlay[0]) {
                $overlay.remove();
            }
        });

        return $overlay;
    }

    function fallbackCopyToClipboard(text) {
        var $temp = $('<textarea></textarea>').val(text).css({ position: 'fixed', top: 0, left: 0, opacity: 0 });
        $('body').append($temp);
        $temp[0].focus();
        $temp[0].select();

        var success = false;
        try {
            success = document.execCommand('copy');
        } catch (e) {
            success = false;
        }

        $temp.remove();
        return success;
    }

    function copyToClipboard(text, callback) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function () {
                callback(true);
            }).catch(function () {
                callback(fallbackCopyToClipboard(text));
            });
        } else {
            callback(fallbackCopyToClipboard(text));
        }
    }

    function getMajorMinor(version) {
        var match = typeof version === 'string' ? version.match(/^(\d+\.\d+)/) : null;
        return match ? match[1] : null;
    }

    function getImportErrorMessage(code) {
        var messages = {
            nonce_invalid: t('error_nonce_invalid'),
            no_permissions: t('error_no_permissions'),
            invalid_payload: t('error_invalid_payload'),
            version_mismatch: t('error_version_mismatch'),
            post_type_mismatch: t('error_post_type_mismatch'),
            field_not_found: t('error_field_not_found')
        };
        return messages[code] || t('error_generic');
    }

    /**
     * Opens a modal to export the current builder content as a file or to the clipboard.
     */
    UFBuilderIO.openExportModal = function (builderData, componentsData, themeVersion, postType) {
        var exportObject = {
            theme_version: themeVersion,
            post_type: postType,
            page_content: builderData,
            page_content_datastore: componentsData
        };
        var json = JSON.stringify(exportObject, null, 2);

        var body =
            '<p>' + t('export_modal_description') + '</p>' +
            '<div class="uf-io-modal-actions">' +
                '<button type="button" class="button button-primary uf-io-download">' + t('download_button') + '</button>' +
                '<button type="button" class="button uf-io-copy">' + t('copy_button') + '</button>' +
            '</div>' +
            '<p class="uf-io-feedback"></p>';

        var $overlay = buildModal(t('export_modal_title'), body);

        $overlay.find('.uf-io-download').on('click', function () {
            var blob = new Blob([json], { type: 'application/json' });
            var url = URL.createObjectURL(blob);
            var $link = $('<a></a>').attr({ href: url, download: 'page-content-export.json' });
            $('body').append($link);
            $link[0].click();
            $link.remove();
            URL.revokeObjectURL(url);
        });

        $overlay.find('.uf-io-copy').on('click', function () {
            copyToClipboard(json, function (success) {
                $overlay.find('.uf-io-feedback').text(
                    success ? t('copied_feedback') : t('copy_fallback_feedback')
                );
            });
        });
    };

    /**
     * Opens a modal to paste/upload an export object and import it into the current post.
     */
    UFBuilderIO.openImportModal = function (postId, fieldName) {
        var body =
            '<p>' + t('import_modal_description') + '</p>' +
            '<input type="file" class="uf-io-file" accept=".json,application/json" />' +
            '<textarea class="uf-io-textarea" rows="10" placeholder="' + t('import_textarea_placeholder') + '"></textarea>' +
            '<p class="uf-io-feedback"></p>' +
            '<div class="uf-io-modal-actions">' +
                '<button type="button" class="button button-primary uf-io-submit">' + t('import_submit') + '</button>' +
                '<button type="button" class="button uf-io-cancel">' + t('import_cancel') + '</button>' +
            '</div>';

        var $overlay = buildModal(t('import_modal_title'), body);

        $overlay.find('.uf-io-cancel').on('click', function () {
            $overlay.remove();
        });

        $overlay.find('.uf-io-file').on('change', function (e) {
            var file = e.target.files[0];
            if (!file) return;

            var reader = new FileReader();
            reader.onload = function (evt) {
                $overlay.find('.uf-io-textarea').val(evt.target.result);
            };
            reader.readAsText(file);
        });

        $overlay.find('.uf-io-submit').on('click', function () {
            var $feedback = $overlay.find('.uf-io-feedback');
            $feedback.text('');

            var raw = $overlay.find('.uf-io-textarea').val();
            var parsed;
            try {
                parsed = JSON.parse(raw);
            } catch (e) {
                $feedback.text(t('invalid_json'));
                return;
            }

            if (
                !parsed || typeof parsed !== 'object' ||
                !parsed.theme_version ||
                !parsed.post_type ||
                !('page_content' in parsed) ||
                !('page_content_datastore' in parsed)
            ) {
                $feedback.text(t('missing_fields'));
                return;
            }

            var currentMajorMinor = getMajorMinor(BUILDER_GLOBALS.theme_version);
            var importedMajorMinor = getMajorMinor(parsed.theme_version);

            if (!importedMajorMinor || importedMajorMinor !== currentMajorMinor) {
                $feedback.text(t('version_mismatch_detail', { imported: parsed.theme_version, current: BUILDER_GLOBALS.theme_version }));
                return;
            }

            if (parsed.post_type !== BUILDER_GLOBALS.posttype) {
                $feedback.text(t('post_type_mismatch_detail', { imported: parsed.post_type, current: BUILDER_GLOBALS.posttype }));
                return;
            }

            var confirmed = window.confirm(t('confirm_overwrite'));
            if (!confirmed) return;

            $overlay.find('.uf-io-submit').prop('disabled', true).text(t('import_submitting'));

            $.post(BUILDER_GLOBALS.ajax_url, {
                action: 'ultimate_builder_import',
                nonce: BUILDER_GLOBALS.import_nonce,
                post_id: postId,
                meta: fieldName,
                data: JSON.stringify(parsed)
            }).done(function (response) {
                if (response && response.success) {
                    window.location.reload();
                } else {
                    $overlay.find('.uf-io-submit').prop('disabled', false).text(t('import_submit'));
                    $feedback.text(getImportErrorMessage(response && response.data));
                }
            }).fail(function () {
                $overlay.find('.uf-io-submit').prop('disabled', false).text(t('import_submit'));
                $feedback.text(t('request_failed'));
            });
        });
    };

})(jQuery);
