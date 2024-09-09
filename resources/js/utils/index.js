export function ref_value(context) {
    return function (refName) {
        const _ref = context.$refs[refName];

        if(_ref instanceof HTMLInputElement)
        {
            if(_ref.type === 'text') return _ref.value.trim();
            if(_ref.type === 'checkbox') return _ref.checked;
        }

        if(_ref instanceof  HTMLSelectElement) return _ref.value.trim();
        if(_ref instanceof  HTMLTextAreaElement) return _ref.value.trim();
        if(_ref instanceof HTMLDivElement) return _ref.innerHTML.trim();

        return _ref?.value?.trim() || '';
    }
}


export function ref_value_set(context) {
    return function (refName, value) {
        const _ref = context.$refs[refName];

        if(_ref instanceof HTMLInputElement)
        {
            if(_ref.type === 'text') _ref.value = value;
            if(_ref.type === 'checkbox') _ref.checked = value;
        }

        if(_ref instanceof  HTMLSelectElement) _ref.value = value;
        if(_ref instanceof  HTMLTextAreaElement) _ref.value = value;
        if(_ref instanceof HTMLDivElement) return _ref.innerHTML = value;

        return 'unknown ref type';
    }
}
