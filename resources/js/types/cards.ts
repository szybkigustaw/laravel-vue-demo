export type CardListItem = {
    id: number;
    card_number: string;
    masked_pin: string;
    activation_date: string;
    expiration_date: string;
    balance: string;
};

export type EditableCard = Omit<CardListItem, 'masked_pin'> & {
    pin: string;
};

export type CardFormData = {
    card_number: string;
    pin: string;
    activation_date: string;
    expiration_date: string;
    balance: string;
};

export type PaginatedCards = {
    data: CardListItem[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    prev_page_url: string | null;
    next_page_url: string | null;
};
