import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';
import CardPagination from './CardPagination.vue';

function mountPagination(overrides = {}) {
    return mount(CardPagination, {
        props: {
            currentPage: 2,
            lastPage: 3,
            previousUrl: '/cards?page=1',
            nextUrl: '/cards?page=3',
            ...overrides,
        },
        global: {
            stubs: {
                Link: {
                    props: ['href'],
                    template: '<a :href="href"><slot /></a>',
                },
            },
        },
    });
}

describe('CardPagination', () => {
    it('shows the current position and navigable page links', () => {
        const wrapper = mountPagination();

        expect(wrapper.get('nav').text()).toContain('Page 2 of 3');
        expect(wrapper.get('a[href="/cards?page=1"]').text()).toContain(
            'Previous',
        );
        expect(wrapper.get('a[href="/cards?page=3"]').text()).toContain('Next');
    });

    it('disables unavailable navigation directions', () => {
        const wrapper = mountPagination({
            currentPage: 1,
            previousUrl: null,
        });

        expect(wrapper.get('button').text()).toContain('Previous');
        expect(wrapper.get('button').attributes('disabled')).toBeDefined();
    });

    it('does not render pagination for a single page', () => {
        const wrapper = mountPagination({
            currentPage: 1,
            lastPage: 1,
            previousUrl: null,
            nextUrl: null,
        });

        expect(wrapper.find('nav').exists()).toBe(false);
    });
});
