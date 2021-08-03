import {useEffect, useState} from "react";

const {registerBlockType} = wp.blocks;

const {decodeEntities} = wp.htmlEntities;

const {SelectControl} = wp.components;

registerBlockType('starter-npm/block-name', {
    title: 'Block Name',
    icon: 'admin-page',
    category: 'starter-npm',
    supports: {
        defaultStylePicker: false,
        multiple: false,
    },
    attributes: {
			attr: {
            type: "string",
            default: ""
        },

    },
    edit: (props) => {
        const {className, attributes, setAttributes} = props;

        return (
            <div className={className}>

            </div>
        );
    },
    save: () => {
        return null;
    },
});
